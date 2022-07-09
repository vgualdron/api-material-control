<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\RoleServiceImplement;
use App\Validator\RoleValidator;
use App\Models\Role;

class RoleController extends Controller
{
   
    private $roleService;
    private $request;
    private $validator;  
    private $model;

    public function __construct(RoleServiceImplement $roleService, Request $request, RoleValidator $validator){      
        $this->roleService = $roleService;
        $this->model = new Role;        
        $this->request = $request;
        $this->validator = $validator;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function list($perPage, $page, $text){
        $text = trim(urldecode($text));
        return response($this->roleService->list($perPage, $page, $text));
    }

    function get($id){
        try {  
            $this->model->findOrFail($id);
            return $this->roleService->get($id);
        } catch (\Exception $e) {
            $message = 'Error al obtener datos de rol';            
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
            $roleModel = $this->roleService->insert($this->request->all());
            $response = response([                
                "message" => "Rol creado con éxito",
                "data" => $roleModel             
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al registrar rol';
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
            $roleModel = $this->roleService->update($this->request->all(), $id);
            $response = response([
                "message" => "Rol actualizado con éxito",
                "data" => $roleModel
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al actualizar rol';
            $response = $this->controlExceptions((!empty($validator) ? $validator : null), $e, '', $message);
            
        }
        return $response;
    }

    function delete($id){
        try {     
            $this->model->findOrFail($id);        
            $this->roleService->delete($id);            
            $response = response([                
                "message" => "Rol eliminado con éxito"                           
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al eliminar rol';
            $response = $this->controlExceptions(null, $e, 'El rol', $message);            
        }
        return $response;
    }
}


