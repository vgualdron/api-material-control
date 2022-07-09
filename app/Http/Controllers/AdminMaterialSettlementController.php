<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\AdminMaterialSettlementServiceImplement;
use App\Models\Settlement;

class AdminMaterialSettlementController extends Controller
{
   
    private $request;
    private $service;
    private $model;

    public function __construct(AdminMaterialSettlementServiceImplement $service, Request $request){      
        $this->service = $service;
        $this->request = $request;
        $this->model = new Settlement;       
        $this->middleware('validate', ['except' => []
        ]);
    }

    function list($perPage, $page, $text, $settlement){
        $text = trim(urldecode($text));
        return response($this->service->list($perPage, $page, $text, $settlement));
    }
    
    function get($id) {
        try {  
            $this->model->findOrFail($id);
            return $this->service->get($id);
        } catch (\Exception $e) {
            $message = 'Error al obtener datos de liquidación';            
            $response = $this->controlExceptions(null, $e, '', $message);            
        }
        return $response;
    }
    
    function update(int $id){
        try {            
            $this->model->findOrFail($id);
            $model = $this->service->update($this->request->all(), $id);
            $response = response([
                "message" => "Liquidación actualizada con éxito",
                "data" => $model
            ], 201);
        } catch (\Exception $e) {
            echo $e->getMessage();
            $message = 'Error al actualizar liquidación';
            $response = $this->controlExceptions(null, $e, '', $message);
            
        }
        return $response;
    }
}


