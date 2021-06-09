<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\YardServiceImplement;
use App\Validator\YardValidator;
use App\Models\Yard;

class YardController extends Controller
{
   
    private $yardService;
    private $request;
    private $validator;  
    private $model;

    public function __construct(YardServiceImplement $yardService, Request $request, YardValidator $validator){      
        $this->yardService = $yardService;
        $this->model = new Yard;        
        $this->request = $request;
        $this->validator = $validator;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function list(){  
        return response($this->yardService->list());
    }

    function get($id){
        try {        
            $this->model->findOrFail($id);   
            return $this->yardService->get($id);
        } catch (\Exception $e) {
            $message = 'Error al obtener datos del patio';            
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
            $yardModel = $this->yardService->insert($this->request->all());
            $response = response([                
                "message" => "Patio creado con éxito",
                "data" => $yardModel             
            ], 201);
        } catch (\Exception $e) {            
            $message = 'Error al registrar patio';
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
            $yardModel = $this->yardService->update($this->request->all(), $id);
            $response = response([
                "message" => "Patio actualizado con éxito",
                "data" => $yardModel
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al actualizar patio';
            $response = $this->controlExceptions((!empty($validator) ? $validator : null), $e, $message);
            
        }
        return $response;
    }

    function delete($id){
        try {     
            $this->model->findOrFail($id);        
            $this->yardService->delete($id);            
            $response = response([                
                "message" => "Patio eliminado con éxito"                           
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al eliminar patio';
            $response = $this->controlExceptions(null, $e, $message);            
        }
        return $response;
    }
}


