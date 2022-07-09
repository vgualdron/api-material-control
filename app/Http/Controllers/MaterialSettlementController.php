<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\MaterialSettlementServiceImplement;
use App\Models\Settlement;

class MaterialSettlementController extends Controller
{
    private $service;
    private $request;
    private $model;

    public function __construct(MaterialSettlementServiceImplement $service, Request $request){
        $this->service = $service;
        $this->request = $request;
        $this->model = new Settlement();
        $this->middleware('validate', ['except' => []
        ]);
        
    }

    function list($type, $start_date, $final_date, $third, $material, $material_type){
        return response($this->service->list($type, $start_date, $final_date, $third, $material, $material_type));
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
            $response = $this->controlExceptions(null, $e, '', $message);            
        }
        return $response;
    }
}


