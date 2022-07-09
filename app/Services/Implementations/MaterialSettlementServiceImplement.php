<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\MaterialSettlementServiceInterface;
    use Illuminate\Support\Facades\DB;
    use App\Models\Settlement;
    use App\Models\Tiquet;
    
    class MaterialSettlementServiceImplement implements MaterialSettlementServiceInterface{

        function __construct(){
            
        }    

        function list(string $type, string $start_date, string $final_date, int $third, int $material, string $material_type){
            try {
                $tiquets = DB::table('tiquet as t')
                                    ->select('t.id as id', DB::Raw('CASE t.type WHEN "C" THEN "COMPRA" WHEN "V" THEN "VENTA" END as type'), 't.type as type_code', 't.date as date',
                                            DB::Raw('IF(t.type = "C", receipt_number, "") as receipt_number'), DB::Raw('IF(t.type = "V", referral_number, "") as referral_number'),
                                            'm.name as material_name', DB::Raw('0 as aux_net_weight'), DB::Raw('IF(t.type = "C", t.supplier_name,  oy.name) as origin_supplier'),
                                            DB::Raw('IF(t.type = "V", t.customer_name,  dy.name) as destiny_customer'), DB::Raw('IF(m.unit = "U", 1, FORMAT(t.net_weight, 2)) as net_weight'),
                                            DB::Raw('FORMAT(IF(t.type = "C", (COALESCE(r.material_price, 0) * IF(m.unit = "U", 1, t.net_weight)), COALESCE(r.net_price,0)), 2) as net_price'), 'm.unit as material_unit',
                                            DB::Raw('FORMAT(IF(t.type = "C", COALESCE(r.material_price, 0), COALESCE(r.net_price, 0)), 2) as material_price'))
                                    ->join('material as m', 't.material', '=', 'm.id')
                                    ->leftJoin('yard as oy', 't.origin_yard', '=', 'oy.id')
                                    ->leftJoin('yard as dy', 't.destiny_yard', '=', 'dy.id')
                                    ->leftJoin('rate as r', function($join) use($type)
                                         {
                                            $join->on('t.type', '=', 'r.movement');
                                            $join->on(DB::Raw('IF("'.$type.'" = "C", t.destiny_yard, t.origin_yard)'), '=', DB::Raw('IF("'.$type.'" = "C", r.destiny_yard, r.origin_yard)'));
                                            $join->on(DB::Raw('IF("'.$type.'" = "C", t.supplier, t.customer)'), '=', DB::Raw('IF("'.$type.'" = "C", r.supplier, r.customer)'));
                                            $join->on('t.date', '>=', 'r.start_date');
                                            $join->on('t.date', '<=', 'r.final_date');
                                            $join->on('t.material', '=', 'r.material');
                                         })
                                    ->where('t.type', '=', $type)
                                    ->whereRaw('IF('.$material.' = 0, TRUE, t.material = '.$material.')')
                                    ->whereRaw('IF(t.type = "C", t.supplier, t.customer) = '.$third)
                                    ->whereNull('t.material_settlement')
                                    ->whereBetween('t.date', [$start_date, $final_date])
                                    ->where('m.unit', '=', $material_type)
                                    ->get();
                                    
                return $tiquets;
            } catch (\Exception $e) {
                echo $e->getMessage().'  '.$e->getLine();
            }
        }
        
        function settle(Array $data) {
            try {
                $settlementReturn = [];
                $settlementReturn = DB::transaction(function() use ($data) {
                    /*consecutive*/
                    $searchConsecutive = DB::table('settlement')
                                                    ->select(DB::Raw('MAX(CAST(consecutive AS UNSIGNED)) as consecutive'))
                                                    ->get()
                                                    ->first();
                                                    
                    $consecutive = $searchConsecutive->consecutive;
                    $consecutive = str_pad(((!empty($consecutive) ? $consecutive : 0) + 1), 10, "0", STR_PAD_LEFT);
                    /*set settlement*/
                    $settlement = new Settlement();
                    $settlement->date = date('Y-m-d');
                    $settlement->type = $data['type'];
                    $settlement->third = $data['third'];
                    $settlement->subtotal_amount = $data['subtotalWeight'];
                    $settlement->subtotal_settlement = $data['subtotalSettle'];
                    $settlement->retentions_percentage = $data['retentionPercentage'];
                    $settlement->retentions = $data['retention'];
                    $settlement->unit_royalties = $data['royaltiesBase'];
                    $settlement->royalties = $data['royalties'];
                    $settlement->total_settle = $data['totalSettle'];
                    $settlement->start_date = $data['startDate'];
                    $settlement->final_date = $data['finalDate'];
                    $settlement->observation = $data['observations'];
                    $settlement->consecutive = $consecutive;
                    $settlement->save();
                    $lastInsertId = $settlement->id;
                    
                    $tiquets = $data['tiquets'];
                    foreach ($tiquets as $item) {
                        $tiquet = Tiquet::where('id', $item['id'])
                                        ->get()
                                        ->first();
                        $tiquet->material_settlement = $lastInsertId;
                        $tiquet->material_settlement_retention_percentage = $data['retentionPercentage'];
                        $tiquet->material_settlement_royalties = $data['royaltiesBase'];
                        $tiquet->material_settlement_unit_value = $item['unit_value'];
                        $tiquet->material_settlement_net_value = $item['net_value'];
                        $tiquet->material_settle_receipt_weight = $item['settle_receipt_weight'];
                        $tiquet->material_weight_settled = $item['weight_settled'];
                        $tiquet->save();
                    }
                    
                    $sql = DB::table('settlement')->select('id','consecutive', 'third', DB::Raw('DATE_FORMAT(date, "%d/%m/%Y") as date'), DB::Raw('FORMAT(subtotal_amount, 2) as subtotalAmount'),  DB::Raw('FORMAT(subtotal_settlement, 2) as subtotalSettlement'),
                                                    DB::Raw('FORMAT(retentions_percentage, 2) as retentionsPercentage'),  DB::Raw('FORMAT(retentions, 2) as retentions'), DB::Raw('FORMAT(unit_royalties, 2) as unitRoyalties'),
                                                    DB::Raw('FORMAT(royalties, 2) as royalties'), DB::Raw('FORMAT(total_settle, 2) as totalSettle'), 'observation', 'invoice', 'invoice_date as invoiceDate', 
                                                    'internal_document as internalDocument', DB::Raw('DATE_FORMAT(start_date, "%d/%m/%Y") as startDate'), DB::Raw('DATE_FORMAT(final_date, "%d/%m/%Y") as finalDate'))
                                                ->where('id', $lastInsertId)
                                                ->get()
                                                ->first();
            
                    $settlement = null;
                    $settlement = [];
                    $settlement['id'] = $sql->id;
                    $settlement['consecutive'] = $sql->consecutive;
                    $settlement['date'] = $sql->date;
                    $settlement['third'] = $sql->third;
                    $settlement['subtotalAmount'] = $sql->subtotalAmount;
                    $settlement['subtotalSettlement'] = $sql->subtotalSettlement;
                    $settlement['unitRoyalties'] = $sql->unitRoyalties;
                    $settlement['royalties'] = $sql->royalties;
                    $settlement['retentionsPercentage'] = $sql->retentionsPercentage;
                    $settlement['retentions'] = $sql->retentions;
                    $settlement['observation'] = $sql->observation;
                    $settlement['invoice'] = $sql->invoice;
                    $settlement['invoiceDate'] = $sql->invoiceDate;
                    $settlement['internalDocument'] = $sql->internalDocument;
                    $settlement['startDate'] = $sql->startDate;
                    $settlement['finalDate'] = $sql->finalDate;
                    $settlement['totalSettle'] = $sql->totalSettle;
                    
                    
                    $tiquets =  DB::table('tiquet as t')->select(DB::Raw('CASE t.type WHEN "D" THEN "TRASLADO" WHEN "C" THEN "COMPRA" ELSE "VENTA" END as type'), 't.referral_number as referral_number', DB::Raw('DATE_FORMAT(date, "%d/%m/%Y") as date'),
                                                                't.receipt_number as receipt_number',  DB::Raw('CASE t.type WHEN "D" THEN dy.name WHEN "C" THEN dy.name ELSE REPLACE(t.customer_name, "/", " / ") END as destiny_customer'),
                                                                DB::Raw('CASE t.type WHEN "D" THEN oy.name WHEN "C" THEN REPLACE(t.supplier_name, "/", " / ") ELSE oy.name END as origin_supplier'), 't.license_plate', 'm.name as material_name',
                                                                DB::Raw('FORMAT(material_settlement_unit_value, 2) as unit_value'), 'material_settle_receipt_weight', 'material_weight_settled', DB::Raw('FORMAT(t.material_settlement_net_value, 2) as material_settlement_net_value'))
                                                        ->join('material as m', 't.material', '=', 'm.id')
                                                        ->leftJoin('yard as oy', 't.origin_yard', '=', 'oy.id')
                                                        ->leftJoin('yard as dy', 't.destiny_yard', '=', 'dy.id')
                                                        ->where('t.material_settlement', $lastInsertId)
                                                        ->get()
                                                        ->toArray();
                    
                    $settlement['tiquets'] = $tiquets;
                    
                    return $settlement;
                });
                return $settlementReturn;
            } catch (\Exception $e) {
                return [];
            }
        }
    }
?>