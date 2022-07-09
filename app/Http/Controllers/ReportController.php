<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\ReportServiceImplement;

class ReportController extends Controller
{
    private $service;

    public function __construct(ReportServiceImplement $service){
        $this->service = $service;
        $this->middleware('validate', ['except' => []
        ]);
    }
    
    function movementsReport($movement, $start_date, $final_date, $origin_yard_supplier, $destiny_yard_customer, $material){
        return response($this->service->movementsReport($movement, $start_date, $final_date, $origin_yard_supplier, $destiny_yard_customer, $material));
    }
    
    function yardStockReport($date){
        return response($this->service->yardStockReport($date));
    }
    
    function completeTransfersReport($start_date, $final_date, $origin_yard, $destiny_yard){
        return response($this->service->completeTransfersReport($start_date, $final_date, $origin_yard, $destiny_yard));
    }
    
    function uncompleteTransfersReport($start_date, $final_date, $origin_yard, $destiny_yard){
        return response($this->service->uncompleteTransfersReport($start_date, $final_date, $origin_yard, $destiny_yard));
    }
    
    function unbilledPurchasesReport($start_date, $final_date, $supplier, $material){
        return response($this->service->unbilledPurchasesReport($start_date, $final_date, $supplier, $material));
    }
    
    function unbilledSalesReport($start_date, $final_date, $customer, $material){
        return response($this->service->unbilledSalesReport($start_date, $final_date, $customer, $material));
    }
    
    function unbilledFreightReport($start_date, $final_date, $conveyor_company, $material){
        return response($this->service->unbilledFreightReport($start_date, $final_date, $conveyor_company, $material));
    }
}


