<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\AdjustmentServiceImplement;
use App\Validator\AdjustmentValidator;
use App\Models\Adjustment;

class AdjustmentController extends Controller
{
   
    private $service;
    private $request;
    private $validator;  
    private $model;

    public function __construct(AdjustmentServiceImplement $service, Request $request, AdjustmentValidator $validator){
        $this->service = $service;
        $this->model = new Adjustment;     
        $this->request = $request;
        $this->validator = $validator;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function list($perPage, $page, $text, $adjustment){
        $text = trim(urldecode($text));
        return response($this->service->list($perPage, $page, $text, $adjustment));
    }

    function get($id){
        try {  
            $this->model->findOrFail($id);
            return $this->service->get($id);
        } catch (\Exception $e) {
            $message = 'Error al obtener datos de ajuste';            
            $response = $this->controlExceptions(null, $e, '', $message);            
        }
        return $response;
    }

    function insert(){          
        try { 
            $validator = $this->validator->validate();            
            if($validator->fails()){               
                trigger_error("Error de validación", E_USER_ERROR);             
            }         
            $model = $this->service->insert($this->request->all());
            $response = response([                
                "message" => "Ajuste creado con éxito",
                "data" => $model             
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al registrar ajuste';
            $response = $this->controlExceptions($validator, $e, '', $message);            
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
            $model = $this->service->update($this->request->all(), $id);
            $response = response([
                "message" => "Ajuste actualizado con éxito",
                "data" => $model
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al actualizar ajuste';
            $response = $this->controlExceptions((!empty($validator) ? $validator : null), $e, '', $message);
            
        }
        return $response;
    }

    function delete($id){
        try {     
            $this->model->findOrFail($id);        
            $this->service->delete($id);            
            $response = response([                
                "message" => "Ajuste eliminado con éxito"                           
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al eliminar AJUSTE';
            $response = $this->controlExceptions(null, $e, 'El ajuste', $message);            
        }
        return $response;
    }
}


