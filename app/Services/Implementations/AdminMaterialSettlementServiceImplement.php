<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\AdminMaterialSettlementServiceInterface;
    use Illuminate\Support\Facades\DB;
    use App\Models\Settlement;
    
    class AdminMaterialSettlementServiceImplement implements AdminMaterialSettlementServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new Settlement();
        }    

        function list(int $perPage, int $page, string $text, int $settlement){

            $query = $this->model->select('id', 'consecutive', 'date', 'third', 'invoice', DB::Raw('FORMAT(subtotal_settlement, 2) as subtotal_settlement'), 
                                        DB::Raw('FORMAT(retentions, 2) as retentions'), DB::Raw('FORMAT(royalties, 2) as royalties'),
                                        DB::Raw('FORMAT(total_settle, 2) as total_settle'))
                            ->where('id', $settlement)
                            ->get();
        
           
           $mainQuery = $this->model->where(function ($query) use ($text){
                                $query->where('consecutive', 'like', '%'.$text.'%')
                                    ->orWhere('third', 'like', '%'.$text.'%')
                                    ->orWhere('date', 'like', '%'.$text.'%')
                                    ->orWhere('invoice', 'like', '%'.$text.'%')
                                    ->orWhere('internal_document', 'like', '%'.$text.'%');
                                }
                            )
                        ->where('id', '<>', (!empty($query[0]->id) ? $query[0]->id : 0))
                        ->where('type', '=', 'M')
                        ->orderBy('date', 'ASC')
                        ->paginate($perPage, ['id', 'consecutive', 'date', 'third', 'invoice', DB::Raw('FORMAT(subtotal_settlement, 2) as subtotal_settlement'), 
                                            DB::Raw('FORMAT(retentions, 2) as retentions'), DB::Raw('FORMAT(royalties, 2) as royalties'),
                                            DB::Raw('FORMAT(total_settle, 2) as total_settle')],
                                'page', $page);
           
            if(!empty($query[0]))
                $mainQuery->prepend($query[0]);
                
            return $mainQuery;

        }
        
        function get(int $id) {
       
            $sql = DB::table('settlement')->select('id','consecutive', 'third', DB::Raw('DATE_FORMAT(date, "%d/%m/%Y") as date'), DB::Raw('FORMAT(subtotal_amount, 2) as subtotalAmount'),  DB::Raw('FORMAT(subtotal_settlement, 2) as subtotalSettlement'),
                                                    DB::Raw('FORMAT(retentions_percentage, 2) as retentionsPercentage'),  DB::Raw('FORMAT(retentions, 2) as retentions'), DB::Raw('FORMAT(unit_royalties, 2) as unitRoyalties'),
                                                    DB::Raw('FORMAT(royalties, 2) as royalties'), DB::Raw('FORMAT(total_settle, 2) as totalSettle'), 'observation', 'invoice', 'invoice_date as invoiceDate', 
                                                    'internal_document as internalDocument', DB::Raw('DATE_FORMAT(start_date, "%d/%m/%Y") as startDate'), DB::Raw('DATE_FORMAT(final_date, "%d/%m/%Y") as finalDate'))
                                                ->where('id', $id)
                                                ->get()
                                                ->first();
            
            
            
            
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
                                                ->where('t.material_settlement', $id)
                                                ->get()
                                                ->toArray();
            
            $settlement['tiquets'] = $tiquets;
            
            return $settlement;
            
        }
        
        function update(array $data,int $id) {
            $settlement = $this->model->find($id);
            $settlement->invoice = $data['invoice'];
            $settlement->invoice_date = $data['invoiceDate'];
            $settlement->internal_document = $data['internalDocument'];
            $settlement->save();
            return $settlement;
        }
    }
?>