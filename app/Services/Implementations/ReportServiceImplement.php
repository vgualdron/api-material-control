<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\ReportServiceInterface;
    use Illuminate\Support\Facades\DB;
    
    class ReportServiceImplement implements ReportServiceInterface{
        
        private $model;

        function __construct(){

        }    

        function movementsReport(string $movement, string $start_date, string $final_date, int $origin_yard_supplier, int $destiny_yard_customer, int $material){
            try {
                $movement = trim(urldecode($movement));
                $tiquetsTransfer = DB::table('tiquet as t1')
                                    ->select(DB::Raw('DATE_FORMAT(t1.date, "%d/%m/%Y") as date'),
                                            DB::Raw('"TRASLADO" as movement'),
                                            "t2.receipt_number as receipt_number",
                                            "t1.referral_number as referral_number",
                                            't1.license_plate as license_plate',
                                            't1.trailer_number as trailer_number',
                                            't1.driver_name as driver_name',
                                            'oy.name as origin_yard_supplier',
                                            'dy.name as destiny_yard_costumer',
                                            't1.conveyor_company_name as conveyor_company',
                                            'm.name as material',
                                            DB::Raw('FORMAT(t1.net_weight, 2) as origin_net_weight'),
                                            DB::Raw('FORMAT(t2.net_weight, 2) as destiny_net_weight'),
                                            DB::Raw('DATE_FORMAT(t1.date, "%d/%m/%Y") as origin_date'),
                                            DB::Raw('DATE_FORMAT(t2.date, "%d/%m/%Y") as destiny_date'),
                                            DB::Raw('FORMAT(COALESCE(t1.freight_settlement_unit_value, 0), 2) as freight_settlement_unit_value'),
                                            DB::Raw('FORMAT(COALESCE(t1.freight_settlement_net_value, 0), 2) as freight_settlement_net_value'),
                                            DB::Raw('FORMAT(COALESCE(t1.material_settlement_unit_value, 0), 2) as material_settlement_unit_value'),
                                            DB::Raw('FORMAT(COALESCE(t1.material_settlement_net_value, 0), 2) as material_settlement_net_value'),
                                            DB::Raw('FORMAT(COALESCE(ms.unit_royalties, 0), 2) as unit_royalties'),
                                            DB::Raw('FORMAT(COALESCE(ms.royalties, 0), 2) as royalties'),
                                            'fs.consecutive as freight_settlement_consecutive',
                                            'ms.consecutive as material_settlement_consecutive',
                                            DB::Raw('DATE_FORMAT(t1.ticketmov_date, "%d/%m/%Y") as tns_upload_date'),
                                            't1.ticketmovid as tns_id',
                                            DB::Raw('DATE_FORMAT(fs.invoice_date, "%d/%m/%Y") as freight_invoice_date'),
                                            'fs.invoice as freight_invoice',
                                            'fs.internal_document as freight_internal_document',
                                            DB::Raw('DATE_FORMAT(ms.invoice_date, "%d/%m/%Y") as material_invoice_date'),
                                            'ms.invoice as material_invoice',
                                            'ms.internal_document as material_internal_document'
                                            )
                                    ->join('tiquet as t2', function($join)
                                         {
                                             $join->on('t1.referral_number', '=', 't2.referral_number');
                                             $join->on('t2.type','=',DB::raw('"R"'));
                                         })
                                    ->join('yard as oy', 't1.origin_yard', '=', 'oy.id')
                                    ->join('yard as dy', 't2.destiny_yard', '=', 'dy.id')
                                    ->join('material as m', 't1.material', '=', 'm.id')
                                    ->LeftJoin('settlement as fs', 't1.freight_settlement', '=', 'fs.id')
                                    ->LeftJoin('settlement as ms', 't1.material_settlement', '=', 'ms.id')
                                    ->where('t1.type', "=", 'D')
                                    ->whereBetween('t1.date', [$start_date, $final_date])
                                    ->whereRaw('IF("'.$movement.'" = "T" AND '.$origin_yard_supplier.' <> 0, t1.origin_yard = '.$origin_yard_supplier.', true)')
                                    ->whereRaw('IF("'.$movement.'" = "T" AND '.$destiny_yard_customer.' <> 0, t1.destiny_yard = '.$destiny_yard_customer.', true)')
                                    ->whereRaw('IF('.$material.' <> 0, t1.material = '.$material.', true)');
                                    
                $tiquetsSale = DB::table('tiquet as t')
                                    ->select(DB::Raw('DATE_FORMAT(t.date, "%d/%m/%Y") as date'),
                                            DB::Raw('"VENTA" as movement'),
                                            't.receipt_number as receipt_number',
                                            "t.referral_number as referral_number",
                                            't.license_plate as license_plate',
                                            't.trailer_number as trailer_number',
                                            't.driver_name as driver_name',
                                            'y.name as origin_yard_supplier',
                                            't.customer_name as destiny_yard_costumer',
                                            't.conveyor_company_name as conveyor_company',
                                            'm.name as material',
                                            DB::Raw('FORMAT(t.net_weight, 2) as origin_net_weight'),
                                            DB::Raw('0.00 as destiny_net_weight'),
                                            DB::Raw('DATE_FORMAT(t.date, "%d/%m/%Y") as origin_date'),
                                            DB::Raw('"" as destiny_date'),
                                            DB::Raw('FORMAT(COALESCE(t.freight_settlement_unit_value, 0), 2) as freight_settlement_unit_value'),
                                            DB::Raw('FORMAT(COALESCE(t.freight_settlement_net_value, 0), 2) as freight_settlement_net_value'),
                                            DB::Raw('FORMAT(COALESCE(t.material_settlement_unit_value, 0), 2) as material_settlement_unit_value'),
                                            DB::Raw('FORMAT(COALESCE(t.material_settlement_net_value, 0), 2) as material_settlement_net_value'),
                                            DB::Raw('FORMAT(COALESCE(ms.unit_royalties, 0), 2) as unit_royalties'),
                                            DB::Raw('FORMAT(COALESCE(ms.royalties, 0), 2) as royalties'),
                                            'fs.consecutive as freight_settlement_consecutive',
                                            'ms.consecutive as material_settlement_consecutive',
                                            DB::Raw('DATE_FORMAT(t.ticketmov_date, "%d/%m/%Y") as tns_upload_date'),
                                            't.ticketmovid as tns_id',
                                            DB::Raw('DATE_FORMAT(fs.invoice_date, "%d/%m/%Y") as freight_invoice_date'),
                                            'fs.invoice as freight_invoice',
                                            'fs.internal_document as freight_internal_document',
                                            DB::Raw('DATE_FORMAT(ms.invoice_date, "%d/%m/%Y") as material_invoice_date'),
                                            'ms.invoice as material_invoice',
                                            'ms.internal_document as material_internal_document'
                                            )
                                    ->join('yard as y', 't.origin_yard', '=', 'y.id')
                                    ->join('material as m', 't.material', '=', 'm.id')
                                    ->LeftJoin('settlement as fs', 't.freight_settlement', '=', 'fs.id')
                                    ->LeftJoin('settlement as ms', 't.material_settlement', '=', 'ms.id')
                                    ->where('t.type', "=", 'V')
                                    ->whereBetween('t.date', [$start_date, $final_date])
                                    ->whereRaw('IF("'.$movement.'" = "V" AND '.$origin_yard_supplier.' <> 0, t.origin_yard = '.$origin_yard_supplier.', true)')
                                    ->whereRaw('IF("'.$movement.'" = "V" AND '.$destiny_yard_customer.' <> 0, t.customer = '.$destiny_yard_customer.', true)')
                                    ->whereRaw('IF('.$material.' <> 0, t.material = '.$material.', true)');
                                    
                $tiquetsPurchase = DB::table('tiquet as t')
                                    ->select(DB::Raw('DATE_FORMAT(t.date, "%d/%m/%Y") as date'),
                                            DB::Raw('"COMPRA" as movement'),
                                            't.receipt_number as receipt_number',
                                            't.referral_number as referral_number',
                                            't.license_plate as license_plate',
                                            't.trailer_number as trailer_number',
                                            't.driver_name as driver_name',
                                            't.supplier_name as origin_yard_supplier',
                                            'y.name as destiny_yard_customer',
                                            't.conveyor_company_name as conveyor_company',
                                            'm.name as material',
                                            DB::Raw('0.00 as origin_net_weight'),
                                            DB::Raw('FORMAT(t.net_weight, 2) as destiny_net_weight'),
                                            DB::Raw('"" as origin_date'),
                                            DB::Raw('DATE_FORMAT(t.date, "%d/%m/%Y") as destiny_date'),
                                            DB::Raw('FORMAT(COALESCE(t.freight_settlement_unit_value, 0), 2) as freight_settlement_unit_value'),
                                            DB::Raw('FORMAT(COALESCE(t.freight_settlement_net_value, 0), 2) as freight_settlement_net_value'),
                                            DB::Raw('FORMAT(COALESCE(t.material_settlement_unit_value, 0), 2) as material_settlement_unit_value'),
                                            DB::Raw('FORMAT(COALESCE(t.material_settlement_net_value, 0), 2) as material_settlement_net_value'),
                                            DB::Raw('FORMAT(COALESCE(ms.unit_royalties, 0), 2) as unit_royalties'),
                                            DB::Raw('FORMAT(COALESCE(ms.royalties, 0), 2) as royalties'),
                                            'fs.consecutive as freight_settlement_consecutive',
                                            'ms.consecutive as material_settlement_consecutive',
                                            DB::Raw('DATE_FORMAT(t.ticketmov_date, "%d/%m/%Y") as tns_upload_date'),
                                            't.ticketmovid as tns_id',
                                            DB::Raw('DATE_FORMAT(fs.invoice_date, "%d/%m/%Y") as freight_invoice_date'),
                                            'fs.invoice as freight_invoice',
                                            'fs.internal_document as freight_internal_document',
                                            DB::Raw('DATE_FORMAT(ms.invoice_date, "%d/%m/%Y") as material_invoice_date'),
                                            'ms.invoice as material_invoice',
                                            'ms.internal_document as material_internal_document'
                                            )
                                    ->join('yard as y', 't.destiny_yard', '=', 'y.id')
                                    ->join('material as m', 't.material', '=', 'm.id')
                                    ->LeftJoin('settlement as fs', 't.freight_settlement', '=', 'fs.id')
                                    ->LeftJoin('settlement as ms', 't.material_settlement', '=', 'ms.id')
                                    ->where('t.type', "=", 'C')
                                    ->whereBetween('t.date', [$start_date, $final_date])
                                    ->whereRaw('IF("'.$movement.'" = "C" AND '.$origin_yard_supplier.' <> 0, t.supplier = '.$origin_yard_supplier.', true)')
                                    ->whereRaw('IF("'.$movement.'" = "C" AND '.$destiny_yard_customer.' <> 0, t.destiny_yard = '.$destiny_yard_customer.', true)')
                                    ->whereRaw('IF('.$material.' <> 0, t.material = '.$material.', true)');
            
                if($movement == '') {
                    $tiquets = $tiquetsTransfer->union($tiquetsSale)->union($tiquetsPurchase);
                } else if($movement == 'C') {
                    $tiquets = $tiquetsPurchase;
                } else {
                   $tiquets = $tiquetsSale;
                }
                                    
                $tiquets = $tiquets->get();
                                    
                return $tiquets;
            } catch (\Exception $e) {
                return [];
            }
        }
        
        function yardStockReport(string $date){
            try{
                $tiquetsOut = DB::table('tiquet as t')
                                    ->select('y.id as yard', 'y.name as yard_name', 'm.id as material', 'm.name as material_name', 't.type as type', 't.net_weight as amount', DB::Raw('"TONELADA" as unit'))
                                    ->join('yard as y', 't.origin_yard', '=', 'y.id')
                                    ->join('material as m', 't.material', '=', 'm.id')
                                    ->where(function ($query) {
                                        $query->where('type', '=', 'D')
                                            ->orWhere('type', '=', 'V');
                                        }
                                    )
                                    ->where('m.unit', '=', 'T')
                                    ->where('t.date', '<=', $date);
                                    
                $tiquetsIn = DB::table('tiquet as t')
                                    ->select('y.id as yard', 'y.name as yard_name', 'm.id as material', 'm.name as material_name', 't.type as type', 't.net_weight as amount', DB::Raw('"TONELADA" as unit'))
                                    ->join('yard as y', 't.destiny_yard', '=', 'y.id')
                                    ->join('material as m', 't.material', '=', 'm.id')
                                    ->where(function ($query) {
                                        $query->where('type', '=', 'R')
                                            ->orWhere('type', '=', 'C');
                                        }
                                    )
                                    ->where('m.unit', '=', 'T')
                                    ->where('t.date', '<=', $date);
                                    
                $adjustment = DB::table('adjustment as a')
                                    ->select('y.id as yard', 'y.name as yard_name', 'm.id as material', 'm.name as material_name', 'a.type as type', 'a.amount as amount', DB::Raw('"TONELADA" as unit'))
                                    ->join('yard as y', 'a.yard', '=', 'y.id')
                                    ->join('material as m', 'a.material', '=', 'm.id')
                                    ->where('a.date', '<=', $date)
                                    ->where('m.unit', '=', 'T')
                                    ->union($tiquetsOut)
                                    ->union($tiquetsIn);
                                    
                $stocks = DB::table($adjustment)->select('yard_name as yard', 'material_name as material', DB::Raw('FORMAT(SUM(IF(type = "C" OR type = "R" OR type = "A", amount, amount*(-1))), 2) as amount'), 'unit')
                                                ->groupBy('yard')
                                                ->groupBy('material')
                                                ->get();
                
                return $stocks;
            } catch (\Exception $e) {
                return [];
            }
        }
        
        function completeTransfersReport(string $start_date, string $final_date, int $origin_yard, int $destiny_yard){
            try {
                $tiquets = DB::table('tiquet as t1')
                                ->select(
                                    DB::Raw('"TRASLADO" as movement'),
                                    't1.referral_number as referral_number',
                                    't2.receipt_number as receipt_number',
                                    DB::Raw('DATE_FORMAT(t1.date, "%d/%m/%Y") as origin_date'),
                                    DB::Raw('DATE_FORMAT(t2.date, "%d/%m/%Y") as destiny_date'),
                                    't1.license_plate as origin_license_plate',
                                    't2.license_plate as destiny_license_plate',
                                    't1.trailer_number as origin_trailer_number',
                                    't2.trailer_number as destiny_trailer_number',
                                    't1.driver_name as origin_driver_name',
                                    't2.driver_name as destiny_driver_name',
                                    't1.driver_document as origin_driver_document',
                                    't2.driver_document as destiny_driver_document',
                                    'ooy.name as origin_origin_yard',
                                    'ody.name as origin_destiny_yard',
                                    'doy.name as destiny_origin_yard',
                                    'ddy.name as destiny_destiny_yard',
                                    't1.conveyor_company_name as origin_conveyor_company_name',
                                    't2.conveyor_company_name as destiny_conveyor_company_name',
                                    'om.name as origin_material',
                                    'dm.name as destiny_material',
                                    DB::Raw('FORMAT(t1.net_weight, 2) as origin_net_weight'),
                                    DB::Raw('FORMAT(t2.net_weight, 2) as destiny_net_weight'),
                                    DB::Raw('FORMAT(ABS(t1.net_weight-t2.net_weight), 2) as weight_differences'),
                                    DB::Raw('FORMAT(ABS((((ABS(t1.net_weight/t2.net_weight))*100)-100)), 2) as percent_weight_differences')
                                )
                                ->join('tiquet as t2', function($join)
                                     {
                                         $join->on('t1.referral_number', '=', 't2.referral_number');
                                         $join->on('t2.type', '=', DB::raw('"R"'));
                                     })
                                ->join('material as om', 't1.material', '=', 'om.id')
                                ->join('material as dm', 't2.material', '=', 'dm.id')
                                ->join('yard as ooy', 't1.origin_yard', '=', 'ooy.id')
                                ->join('yard as ody', 't1.destiny_yard', '=', 'ody.id')
                                ->join('yard as doy', 't2.origin_yard', '=', 'doy.id')
                                ->join('yard as ddy', 't2.destiny_yard', '=', 'ddy.id')
                                ->where('t1.type', '=', 'D')
                                ->where(function ($query) use ($start_date, $final_date){
                                    $query->whereBetween('t1.date', [$start_date, $final_date])
                                        ->orWhereBetween('t2.date', [$start_date, $final_date]);
                                })
                                ->where(function ($query) use ($origin_yard){
                                    $query->whereRaw('IF('.$origin_yard.' <> 0, t1.origin_yard = '.$origin_yard.', true)')
                                        ->orWhereRaw('IF('.$origin_yard.' <> 0, t2.origin_yard = '.$origin_yard.', true)');
                                })
                                ->where(function ($query) use ($destiny_yard){
                                    $query->whereRaw('IF('.$destiny_yard.' <> 0, t1.destiny_yard = '.$destiny_yard.', true)')
                                        ->orWhereRaw('IF('.$destiny_yard.' <> 0, t2.destiny_yard = '.$destiny_yard.', true)');
                                })
                                ->get();
                return $tiquets;
            } catch (\Exception $e) {
                return [];
            }
        }
        
        function uncompleteTransfersReport(string $start_date, string $final_date, int $origin_yard, int $destiny_yard){
            try {
                $tiquetsD = DB::table('tiquet as t1')
                                ->select(
                                    DB::Raw('"DESPACHO" as movement'),
                                    't1.referral_number as referral_number',
                                    DB::Raw('"" as receipt_number'),
                                    't1.date as date',
                                    't1.license_plate as license_plate',
                                    't1.trailer_number as trailer_number',
                                    't1.driver_name as driver_name',
                                    't1.driver_document as driver_document',
                                    'oy.name as origin_yard',
                                    'dy.name as destiny_yard',
                                    't1.conveyor_company_name as conveyor_company',
                                    'm.name as material',
                                    't1.net_weight as net_weight'
                                )
                                ->leftJoin('tiquet as t2', function($join)
                                     {
                                         $join->on('t1.referral_number', '=', 't2.referral_number');
                                         $join->on('t2.type', '=', DB::raw('"R"'));
                                     })
                                ->join('material as m', 't1.material', '=', 'm.id')
                                ->join('yard as oy', 't1.origin_yard', '=', 'oy.id')
                                ->join('yard as dy', 't1.destiny_yard', '=', 'dy.id')
                                ->where('t1.type', '=', 'D')
                                ->whereRaw('IF('.$origin_yard.' <> 0, t1.origin_yard = '.$origin_yard.', true)')
                                ->whereRaw('IF('.$destiny_yard.' <> 0, t1.destiny_yard = '.$destiny_yard.', true)')
                                ->whereNull('t2.referral_number')
                                ->whereBetween('t1.date', [$start_date, $final_date]);
                                
                $tiquetsR = DB::table('tiquet as t1')
                                ->select(
                                    DB::Raw('"RECEPCION" as movement'),
                                    DB::Raw('"" as referral_number'),
                                    't1.receipt_number as receipt_number',
                                    't1.date as date',
                                    't1.license_plate as license_plate',
                                    't1.trailer_number as trailer_number',
                                    't1.driver_name as driver_name',
                                    't1.driver_document as driver_document',
                                    'oy.name as origin_yard',
                                    'dy.name as destiny_yard',
                                    't1.conveyor_company_name as conveyor_company',
                                    'm.name as material',
                                    't1.net_weight as net_weight'
                                )
                                ->leftJoin('tiquet as t2', function($join)
                                     {
                                         $join->on('t1.referral_number', '=', 't2.referral_number');
                                         $join->on('t2.type', '=', DB::raw('"D"'));
                                     })
                                ->join('material as m', 't1.material', '=', 'm.id')
                                ->join('yard as oy', 't1.origin_yard', '=', 'oy.id')
                                ->join('yard as dy', 't1.destiny_yard', '=', 'dy.id')
                                ->where('t1.type', '=', 'R')
                                ->whereRaw('IF('.$origin_yard.' <> 0, t1.origin_yard = '.$origin_yard.', true)')
                                ->whereRaw('IF('.$destiny_yard.' <> 0, t1.destiny_yard = '.$destiny_yard.', true)')
                                ->whereNull('t2.referral_number')
                                ->whereBetween('t1.date', [$start_date, $final_date])
                                ->union($tiquetsD)
                                ->get();
                return $tiquetsR;
            } catch (\Exception $e) {
                return [];
            }
        }
        
        function unbilledPurchasesReport(string $start_date, string $final_date, int $supplier, int $material){
            try {
                $tiquets = DB::table('tiquet as t')
                    ->select(
                        DB::Raw('"COMPRA" as movement'),
                        't.referral_number as referral_number',
                        't.receipt_number as receipt_number',
                        DB::Raw('DATE_FORMAT(t.date, "%d/%m/%Y") as date'),
                        't.license_plate as license_plate',
                        't.trailer_number as trailer_number',
                        't.driver_name as driver_name',
                        't.driver_document as driver_document',
                        't.supplier_name as supplier_name',
                        'y.name as destiny_yard',
                        'm.name as material',
                        DB::Raw('FORMAT(COALESCE(t.net_weight, 0), 2) as net_weight'),
                        DB::Raw('FORMAT(COALESCE(t.material_settlement_unit_value, 0), 2) as material_settlement_unit_value'),
                        DB::Raw('FORMAT(COALESCE(t.material_settlement_net_value, 0), 2) as material_settlement_net_value'),
                        DB::Raw('FORMAT(COALESCE(s.unit_royalties, 0), 2) as unit_royalties'),
                        DB::Raw('FORMAT(COALESCE(s.royalties, 0), 2) as royalties'),
                        's.consecutive as settlement_consecutive',
                        't.ticketmov_date as tns_upload_date',
                        't.ticketmovid as tns_id'
                    )
                    ->LeftJoin('settlement as s', 't.material_settlement', '=', 's.id')
                    ->join('material as m', 't.material', '=', 'm.id')
                    ->join('yard as y', 't.destiny_yard', '=', 'y.id')
                    ->where('t.type', 'C')
                    ->whereBetween('t.date', [$start_date, $final_date])
                    ->whereNull('s.invoice')
                    ->whereRaw('IF('.$supplier.' <> 0, t.supplier = '.$supplier.', true)')
                    ->whereRaw('IF('.$material.' <> 0, t.material = '.$material.', true)')
                    ->get();
                    
                return $tiquets;
            } catch (\Exception $e) {
                return [];
            }
        }
        
        function unbilledSalesReport(string $start_date, string $final_date, int $customer, int $material){
            try {
                $tiquets = DB::table('tiquet as t')
                    ->select(
                        DB::Raw('"VENTA" as movement'),
                        't.referral_number as referral_number',
                        DB::Raw('DATE_FORMAT(t.date, "%d/%m/%Y") as date'),
                        't.license_plate as license_plate',
                        't.trailer_number as trailer_number',
                        't.driver_name as driver_name',
                        't.driver_document as driver_document',
                        't.customer_name as customer_name',
                        'y.name as destiny_yard',
                        'm.name as material',
                        DB::Raw('FORMAT(COALESCE(t.net_weight, 0), 2) as net_weight'),
                        DB::Raw('FORMAT(COALESCE(t.material_settlement_unit_value, 0), 2) as material_settlement_unit_value'),
                        DB::Raw('FORMAT(COALESCE(t.material_settlement_net_value, 0), 2) as material_settlement_net_value'),
                        DB::Raw('FORMAT(COALESCE(s.unit_royalties, 0), 2) as unit_royalties'),
                        DB::Raw('FORMAT(COALESCE(s.royalties, 0), 2) as royalties'),
                        's.consecutive as settlement_consecutive',
                        't.ticketmov_date as tns_upload_date',
                        't.ticketmovid as tns_id'
                    )
                    ->LeftJoin('settlement as s', 't.material_settlement', '=', 's.id')
                    ->join('material as m', 't.material', '=', 'm.id')
                    ->join('yard as y', 't.origin_yard', '=', 'y.id')
                    ->where('t.type', 'V')
                    ->whereBetween('t.date', [$start_date, $final_date])
                    ->whereNull('s.invoice')
                    ->whereRaw('IF('.$customer.' <> 0, t.customer = '.$customer.', true)')
                    ->whereRaw('IF('.$material.' <> 0, t.material = '.$material.', true)')
                    ->get();
                    
                return $tiquets;
            } catch (\Exception $e) {
                return [];
            }
        }
        
        function unbilledFreightReport(string $start_date, string $final_date, int $conveyor_company, int $material) {
            try {
                $tiquetsTransfer = DB::table('tiquet as t1')
                                    ->select(
                                        DB::Raw('"TRASLADO" as movement'),
                                        't1.referral_number as referral_number',
                                        't2.receipt_number as receipt_number',
                                        DB::Raw('DATE_FORMAT(t1.date, "%d/%m/%Y") as origin_date'),
                                        DB::Raw('DATE_FORMAT(t2.date, "%d/%m/%Y") as destiny_date'),
                                        't1.license_plate as license_plate',
                                        't1.trailer_number as trailer_number',
                                        't1.driver_name as driver_name',
                                        't1.driver_document as driver_document',
                                        'oy.name as origin_yard_supplier',
                                        'dy.name as destiny_yard_customer',
                                        'm.name as material',
                                        't1.conveyor_company_name as conveyor_company',
                                        DB::Raw('FORMAT(COALESCE(t1.net_weight, 0), 2) as origin_net_weight'),
                                        DB::Raw('FORMAT(COALESCE(t2.net_weight, 0), 2) as destiny_net_weight'),
                                        DB::Raw('FORMAT(COALESCE(t1.freight_settlement_unit_value, 0), 2) as freight_settlement_unit_value'),
                                        DB::Raw('FORMAT(COALESCE(t1.freight_settlement_net_value, 0), 2) as freight_settlement_net_value'),
                                        's.consecutive as settlement_consecutive',
                                        't1.ticketmov_date as tns_upload_date',
                                        't1.ticketmovid as tns_id'
                                    )
                                    ->join('tiquet as t2', function($join) {
                                        $join->on('t1.referral_number', '=', 't2.referral_number');
                                        $join->on('t2.type','=', DB::raw('"R"'));
                                    })
                                    ->join('yard as oy', 't1.origin_yard', '=', 'oy.id')
                                    ->join('yard as dy', 't1.destiny_yard', '=', 'dy.id')
                                    ->join('material as m', 't1.material', '=', 'm.id')
                                    ->LeftJoin('settlement as s', 't1.freight_settlement', '=', 's.id')
                                    ->where('t1.type', '=', 'D')
                                    ->whereNull('s.invoice')
                                    ->whereBetween('t1.date', [$start_date, $final_date])
                                    ->whereRaw('IF('.$conveyor_company.' <> 0, t1.conveyor_company = '.$conveyor_company.', true)')
                                    ->whereRaw('IF('.$material.' <> 0, t1.material = '.$material.', true)');
                                    
                $tiquetsPurchase = DB::table('tiquet as t')
                                ->select(
                                    DB::Raw('"COMPRA" as movement'),
                                    't.referral_number as referral_number',
                                    't.receipt_number as receipt_number',
                                    DB::Raw('"" as origin_date'),
                                    DB::Raw('DATE_FORMAT(t.date, "%d/%m/%Y") as destiniy_date'),
                                    't.license_plate as license_plate',
                                    't.trailer_number as trailer_number',
                                    't.driver_name as driver_name',
                                    't.driver_document as driver_document',
                                    't.supplier_name as origin_yard_supplier',
                                    'y.name as destiny_yard_customer',
                                    'm.name as material',
                                    't.conveyor_company_name as conveyor_company',
                                    DB::Raw('"0.00" as origin_net_weight'),
                                    DB::Raw('FORMAT(COALESCE(t.net_weight, 0), 2) as destiny_net_weight'),
                                    DB::Raw('FORMAT(COALESCE(t.freight_settlement_unit_value, 0), 2) as freight_settlement_unit_value'),
                                    DB::Raw('FORMAT(COALESCE(t.freight_settlement_net_value, 0), 2) as freight_settlement_net_value'),
                                    's.consecutive as settlement_consecutive',
                                    't.ticketmov_date as tns_upload_date',
                                    't.ticketmovid as tns_id'
                                )
                                ->LeftJoin('settlement as s', 't.freight_settlement', '=', 's.id')
                                ->join('material as m', 't.material', '=', 'm.id')
                                ->join('yard as y', 't.destiny_yard', '=', 'y.id')
                                ->where('t.type', 'C')
                                ->whereBetween('t.date', [$start_date, $final_date])
                                ->whereNull('s.invoice')
                                ->whereRaw('IF('.$conveyor_company.' <> 0, t.conveyor_company = '.$conveyor_company.', true)')
                                ->whereRaw('IF('.$material.' <> 0, t.material = '.$material.', true)');
                                
                $tiquetsSale = DB::table('tiquet as t')
                                ->select(
                                    DB::Raw('"VENTA" as movement'),
                                    't.referral_number as referral_number',
                                    't.receipt_number as receipt_number',
                                    DB::Raw('DATE_FORMAT(t.date, "%d/%m/%Y") as origin_date'),
                                    DB::Raw('"" as destiniy_date'),
                                    't.license_plate as license_plate',
                                    't.trailer_number as trailer_number',
                                    't.driver_name as driver_name',
                                    't.driver_document as driver_document',
                                    'y.name as origin_yard_supplier',
                                    't.customer_name as destiny_yard_customer',
                                    'm.name as material',
                                    't.conveyor_company_name as conveyor_company',
                                    DB::Raw('FORMAT(COALESCE(t.net_weight, 0), 2) as origin_net_weight'),
                                    DB::Raw('"0.00" as destiny_net_weight'),
                                    DB::Raw('FORMAT(COALESCE(t.freight_settlement_unit_value, 0), 2) as freight_settlement_unit_value'),
                                    DB::Raw('FORMAT(COALESCE(t.freight_settlement_net_value, 0), 2) as freight_settlement_net_value'),
                                    's.consecutive as settlement_consecutive',
                                    't.ticketmov_date as tns_upload_date',
                                    't.ticketmovid as tns_id'
                                )
                                ->LeftJoin('settlement as s', 't.freight_settlement', '=', 's.id')
                                ->join('material as m', 't.material', '=', 'm.id')
                                ->join('yard as y', 't.origin_yard', '=', 'y.id')
                                ->where('t.type', 'V')
                                ->whereBetween('t.date', [$start_date, $final_date])
                                ->whereNull('s.invoice')
                                ->whereRaw('IF('.$conveyor_company.' <> 0, t.conveyor_company = '.$conveyor_company.', true)')
                                ->whereRaw('IF('.$material.' <> 0, t.material = '.$material.', true)');
                                    
                $tiquets = $tiquetsTransfer->union($tiquetsPurchase)->union($tiquetsSale)->get();
                
                return $tiquets;
            } catch (\Exception $e) {
                return [];
            }
        }
    }
?>