<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\FreightSettlementServiceInterface;
    use Illuminate\Support\Facades\DB;
    use App\Models\Settlement;
    use App\Models\Tiquet;
    
    class FreightSettlementServiceImplement implements FreightSettlementServiceInterface{

        function __construct(){
            
        }    

        function list(string $start_date, string $final_date, int $conveyor_company){
            try {
                $tiquetsTransfer = DB::table('tiquet as t1')
                                    ->select(DB::Raw('CASE t1.type WHEN "D" THEN "TRASLADO" WHEN "C" THEN "COMPRA" WHEN "V" THEN "VENTA" END as type'),
                                            DB::Raw('CASE t1.type WHEN "D" THEN "T" ELSE t1.type END as type_code'),
                                            't1.date as date', 't1.referral_number as referral_number', 't2.receipt_number as receipt_number', 'm.name as material_name', 't1.id as id',
                                            't1.conveyor_company as conveyor_company', 't1.conveyor_company_name as conveyor_company_name', 'oy.name as origin_supplier',
                                            'dy.name as destiny_customer', DB::Raw('IF(m.unit = "U", 1, FORMAT(t1.net_weight, 2)) as net_weight'),
                                            DB::Raw('IF(m.unit = "U", 0, t2.net_weight) as aux_net_weight'), DB::Raw('FORMAT(COALESCE(r.freight_price, 0), 2) as freight_price'),
                                            'm.unit as material_unit', DB::Raw('IF(t1.round_trip = 0, t2.round_trip, t1.round_trip) as round_trip'),
                                            DB::Raw('FORMAT(COALESCE(r.freight_price, 0) *  IF(m.unit = "U", 1, t1.net_weight), 2) as net_price'))
                                    ->join('tiquet as t2', function($join)
                                         {
                                             $join->on('t1.referral_number', '=', 't2.referral_number');
                                             $join->on('t2.type','=',DB::raw('"R"'));
                                         })
                                    ->join('material as m', 't1.material', '=', 'm.id')
                                    ->join('yard as oy', 't1.origin_yard', '=', 'oy.id')
                                    ->join('yard as dy', 't1.destiny_yard', '=', 'dy.id')
                                    ->leftJoin('rate as r', function($join)
                                        {
                                            $join->on('t1.type', '=', DB::raw('IF(r.movement = "T", "D", "")'));
                                            $join->on('t1.origin_yard', '=', 'r.origin_yard');
                                            $join->on('t1.destiny_yard', '=', 'r.destiny_yard');
                                            $join->on('t1.material', '=', 'r.material');
                                            $join->on('t1.conveyor_company', '=', 'r.conveyor_company');
                                            $join->on('t1.date', '>=', 'r.start_date');
                                            $join->on('t1.date', '<=', 'r.final_date');
                                            $join->on(DB::Raw('IF(t1.round_trip = 0, t2.round_trip, t1.round_trip)'), '=', 'r.round_trip');
                                        })
                                    ->where('t1.type', '=', 'D')
                                    ->whereNull('t1.freight_settlement')
                                    ->whereRaw('IF('.$conveyor_company.' <> 0, t1.conveyor_company = '.$conveyor_company.', true)')
                                    ->whereBetween('t1.date', [$start_date, $final_date])
                                    /*->get()*/;
                                    
                $tiquetsSale = DB::table('tiquet as t1')
                                    ->select(DB::Raw('CASE t1.type WHEN "D" THEN "TRASLADO" WHEN "C" THEN "COMPRA" WHEN "V" THEN "VENTA" END as type'),
                                            DB::Raw('CASE t1.type WHEN "D" THEN "T" ELSE t1.type END as type_code'),
                                            't1.date as date', 't1.referral_number as referral_number', 't1.receipt_number as receipt_number', 'm.name as material_name', 't1.id as id',
                                            't1.conveyor_company as conveyor_company', 't1.conveyor_company_name as conveyor_company_name', 'oy.name as origin_supplier',
                                            't1.customer_name as destiny_customer', DB::Raw('IF(m.unit = "U", 1, FORMAT(t1.net_weight, 2)) as net_weight'), 
                                            DB::Raw('0 as aux_net_weight'), DB::Raw('FORMAT(COALESCE(r.freight_price, 0), 2) as freight_price'),
                                            'm.unit as material_unit', DB::Raw('0 as round_trip'),
                                            DB::Raw('FORMAT(COALESCE(r.freight_price, 0) *  IF(m.unit = "U", 1, t1.net_weight), 2) as net_price'))
                                    ->join('material as m', 't1.material', '=', 'm.id')
                                    ->join('yard as oy', 't1.origin_yard', '=', 'oy.id')
                                    ->leftjoin('rate as r', function($join)
                                         {
                                            $join->on('t1.type', '=', 'r.movement');
                                            $join->on('t1.origin_yard', '=', 'r.origin_yard');
                                            $join->on('t1.customer', '=', 'r.customer');
                                            $join->on('t1.date', '>=', 'r.start_date');
                                            $join->on('t1.date', '<=', 'r.final_date');
                                         })
                                    ->where('t1.type', '=', 'V')
                                    ->whereNull('t1.freight_settlement')
                                    //->whereRaw('IF('.$conveyor_company.' <> 0, t1.conveyor_company = '.$conveyor_company.', true)')
                                    ->where('t1.conveyor_company', '=', $conveyor_company)
                                    ->whereBetween('t1.date', [$start_date, $final_date])
                                    /*->get()*/;
                                    
                
                
                $tiquetsPurchase = DB::table('tiquet as t1')
                                    ->select(DB::Raw('CASE t1.type WHEN "D" THEN "TRASLADO" WHEN "C" THEN "COMPRA" WHEN "V" THEN "VENTA" END as type'),
                                            DB::Raw('CASE t1.type WHEN "D" THEN "T" ELSE t1.type END as type_code'),
                                            't1.date as date', 't1.referral_number as referral_number', 't1.receipt_number as receipt_number', 'm.name as material_name', 't1.id as id',
                                            't1.conveyor_company as conveyor_company', 't1.conveyor_company_name as conveyor_company_name', 't1.supplier_name as origin_supplier',
                                            'dy.name as destiny_customer', DB::Raw('IF(m.unit = "U", 1, FORMAT(t1.net_weight, 2)) as net_weight'), DB::Raw('0 as aux_net_weight'), 
                                            DB::Raw('FORMAT(COALESCE(r.freight_price, 0), 2) as freight_price'), 'm.unit as material_unit', DB::Raw('0 as round_trip'),
                                            DB::Raw('FORMAT(COALESCE(r.freight_price, 0) *  IF(m.unit = "U", 1, t1.net_weight), 2) as net_price'))
                                    ->join('material as m', 't1.material', '=', 'm.id')
                                    ->join('yard as dy', 't1.destiny_yard', '=', 'dy.id')
                                    ->leftjoin('rate as r', function($join)
                                         {
                                            $join->on('t1.type', '=', 'r.movement');
                                            $join->on('t1.destiny_yard', '=', 'r.destiny_yard');
                                            $join->on('t1.supplier', '=', 'r.supplier');
                                            $join->on('t1.date', '>=', 'r.start_date');
                                            $join->on('t1.date', '<=', 'r.final_date');
                                         })
                                    ->where('t1.type', '=', 'C')
                                    ->whereNull('t1.freight_settlement')
                                    //->whereRaw('IF('.$conveyor_company.' <> 0, t1.conveyor_company = '.$conveyor_company.', true)')
                                    ->where('t1.conveyor_company', '=', $conveyor_company)
                                    ->whereBetween('t1.date', [$start_date, $final_date])
                                    /*->get()*/;
                                    
                $tiquets = $tiquetsTransfer->union($tiquetsSale)->union($tiquetsPurchase)->get();
                                    
                                    
                return $tiquets;
            } catch (\Exception $e) {
                echo $e->getMessage();
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
                        $tiquet->freight_settlement = $lastInsertId;
                        $tiquet->freight_settlement_retention_percentage = $data['retentionPercentage'];
                        $tiquet->freight_settlement_unit_value = $item['unit_value'];
                        $tiquet->freight_settlement_net_value = $item['net_value'];
                        $tiquet->freight_settle_receipt_weight = $item['settle_receipt_weight'];
                        $tiquet->freight_weight_settled = $item['weight_settled'];
                        $tiquet->save();
                    }
                    
                    $sql = DB::table('settlement')->select('id','consecutive', 'third', DB::Raw('DATE_FORMAT(date, "%d/%m/%Y") as date'), DB::Raw('FORMAT(subtotal_amount, 2) as subtotalAmount'),  DB::Raw('FORMAT(subtotal_settlement, 2) as subtotalSettlement'),
                                                    DB::Raw('FORMAT(retentions_percentage, 2) as retentionsPercentage'),  DB::Raw('FORMAT(retentions, 2) as retentions'), DB::Raw('FORMAT(total_settle, 2) as totalSettle'), 'observation',
                                                    'invoice', 'invoice_date as invoiceDate', 'internal_document as internalDocument', DB::Raw('DATE_FORMAT(start_date, "%d/%m/%Y") as startDate'), DB::Raw('DATE_FORMAT(final_date, "%d/%m/%Y") as finalDate'))
                                                ->where('id', $lastInsertId)
                                                ->get()
                                                ->first();
                    
                    $settlement = null;
                    $settlement['id'] = $sql->id;
                    $settlement['consecutive'] = $sql->consecutive;
                    $settlement['date'] = $sql->date;
                    $settlement['third'] = $sql->third;
                    $settlement['subtotalAmount'] = $sql->subtotalAmount;
                    $settlement['subtotalSettlement'] = $sql->subtotalSettlement;
                    $settlement['retentionsPercentage'] = $sql->retentionsPercentage;
                    $settlement['retentions'] = $sql->retentions;
                    $settlement['observation'] = $sql->observation;
                    $settlement['invoice'] = $sql->invoice;
                    $settlement['invoiceDate'] = $sql->invoiceDate;
                    $settlement['internalDocument'] = $sql->internalDocument;
                    $settlement['startDate'] = $sql->startDate;
                    $settlement['finalDate'] = $sql->finalDate;
                    $settlement['totalSettle'] = $sql->totalSettle;
                    
                    $tiquets =  DB::table('tiquet as t')->select(DB::Raw('CASE t.type WHEN "D" THEN "TRASLADO" WHEN "C" THEN "COMPRA" ELSE "VENTA" END as type'), 't.referral_number as referral_number', DB::Raw('DATE_FORMAT(t.date, "%d/%m/%Y") as date'),
                                                                DB::Raw('IF(COALESCE(t2.receipt_number, "") <> "", t2.receipt_number, t.receipt_number) as receipt_number'), DB::Raw('CASE t.type WHEN "D" THEN dy.name WHEN "C" THEN dy.name ELSE REPLACE(t.customer_name, "/", " / ") END as destiny_customer'),
                                                                DB::Raw('CASE t.type WHEN "D" THEN oy.name WHEN "C" THEN REPLACE(t.supplier_name, "/", " / ") ELSE oy.name END as origin_supplier'), 't.license_plate', DB::Raw('FORMAT(t.freight_settlement_net_value, 2) as freight_settlement_net_value'),
                                                                DB::Raw('FORMAT(t.freight_settlement_unit_value, 2) as unit_value'), 't.freight_settle_receipt_weight', DB::Raw('FORMAT(t.freight_weight_settled, 2) as freight_weight_settled'), 'm.name as material_name',
                                                                DB::Raw('IF(t.round_trip = 0, t2.round_trip, t.round_trip) as round_trip'))
                                                        ->leftJoin('tiquet as t2', function($join) {
                                                             $join->on('t.referral_number', '=', 't2.referral_number');
                                                             $join->on('t2.type','=',DB::raw('"R"'));
                                                        })
                                                        ->join('material as m', 't.material', '=', 'm.id')
                                                        ->leftJoin('yard as oy', 't.origin_yard', '=', 'oy.id')
                                                        ->leftJoin('yard as dy', 't.destiny_yard', '=', 'dy.id')
                                                        ->where('t.freight_settlement', $lastInsertId)
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