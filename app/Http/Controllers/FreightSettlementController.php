<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\FreightSettlementServiceImplement;
use App\Models\Settlement;

class FreightSettlementController extends Controller
{
    private $service;
    private $request;
    private $model;

    public function __construct(FreightSettlementServiceImplement $service, Request $request){
        $this->service = $service;
        $this->request = $request;
        $this->model = new Settlement();
        $this->middleware('validate', ['except' => []
        ]);
        
    }

    function list($start_date, $final_date, $conveyor_company){
        return response($this->service->list($start_date, $final_date, $conveyor_company));
    }
    
    function settle(){
        try {
            $responseData = $this->service->settle($this->request->all());
            if(empty($responseData)) {
                trigger_error("Se ha producido un error en el servidor", E_USER_ERROR);
            }
            $response = response([                
                "message" => "Liquidación número ".$responseData['consecutive']." realizada con éxito",
                "data" => $responseData
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al liquidar fletes';
            $response = $this->controlExceptions(null , $e, '', $message);            
        }
        return $response;
    }
}


