<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\ZoneServiceImplement;
use App\Validator\ZoneValidator;
use App\Models\Zone;

class ZoneController extends Controller
{
   
    private $zoneService;
    private $request;
    private $validator;  
    private $model;

    public function __construct(ZoneServiceImplement $zoneService, Request $request, ZoneValidator $validator){      
        $this->zoneService = $zoneService;
        $this->model = new Zone;        
        $this->request = $request;
        $this->validator = $validator;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function list(){  
        return response($this->zoneService->list());
    }

    function get($id){
        try {  
            $this->model->findOrFail($id);
            return $this->zoneService->get($id);
        } catch (\Exception $e) {
            $message = 'Error al obtener datos de zona';            
            $response = $this->controlExceptions(null, $e, $message);            
        }
        return $response;
    }

    function insert(){          
        try { 
            $validator = $this->validator->validate();            
            if($validator->fails()){               
                trigger_error("Error de validación", E_USER_ERROR);             
            }         
            $zoneModel = $this->zoneService->insert($this->request->all());
            $response = response([                
                "message" => "Zona creada con éxito",
                "data" => $zoneModel             
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al registrar zona';
            $response = $this->controlExceptions($validator, $e, $message);            
        }
        return $response;
    }

    function update(int $id){        
        try {            
            $this->model->findOrFail($id);
            $validator = $this->validator->validate();
            if($validator->fails()){                            
                trigger_error("Error de validación", E_USER_ERROR);
            }
            $zoneModel = $this->zoneService->update($this->request->all(), $id);
            $response = response([
                "message" => "Zona actualizada con éxito",
                "data" => $zoneModel
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al actualizar zona';
            $response = $this->controlExceptions((!empty($validator) ? $validator : null), $e, $message);
            
        }
        return $response;
    }

    function delete($id){
        try {     
            $this->model->findOrFail($id);        
            $this->zoneService->delete($id);            
            $response = response([                
                "message" => "Zona eliminada con éxito"                           
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al eliminar zona';
            $response = $this->controlExceptions(null, $e, $message);            
        }
        return $response;
    }
}


