<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\MaterialServiceImplement;
use App\Validator\MaterialValidator;
use App\Models\Material;

class MaterialController extends Controller
{
   
    private $materialService;
    private $request;
    private $validator;  
    private $model;

    public function __construct(MaterialServiceImplement $materialService, Request $request, MaterialValidator $validator){      
        $this->materialService = $materialService;
        $this->model = new Material;        
        $this->request = $request;
        $this->validator = $validator;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function list(){  
        return response($this->materialService->list());
    }

    function get($id){
        try {  
            $this->model->findOrFail($id);
            return $this->materialService->get($id);
        } catch (\Exception $e) {
            $message = 'Error al obtener datos de material';            
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
            $materialModel = $this->materialService->insert($this->request->all());
            $response = response([                
                "message" => "Material creado con éxito",
                "data" => $materialModel             
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al registrar material';
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
            $materialModel = $this->materialService->update($this->request->all(), $id);
            $response = response([
                "message" => "Material actualizado con éxito",
                "data" => $materialModel
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al actualizar material';
            $response = $this->controlExceptions((!empty($validator) ? $validator : null), $e, $message);
            
        }
        return $response;
    }

    function delete($id){
        try {     
            $this->model->findOrFail($id);        
            $this->materialService->delete($id);            
            $response = response([                
                "message" => "Material eliminado con éxito"                           
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al eliminar material';
            $response = $this->controlExceptions(null, $e, $message);            
        }
        return $response;
    }
}


