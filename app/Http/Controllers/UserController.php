<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\UserServiceImplement;
use App\Validator\UserValidator;
use App\Validator\ProfileValidator;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    private $userService;
    private $request;
    private $validator;
    private $profileValidator;
    private $model;

    public function __construct(UserServiceImplement $userService, Request $request, UserValidator $validator, ProfileValidator $profileValidator){        
        $this->model = new User;
        $this->userService = $userService;
        $this->request = $request;
        $this->validator = $validator;
        $this->profileValidator = $profileValidator;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function list(){
        return response($this->userService->list());
    }

    function get($id){
        try {      
            $this->model->findOrFail($id);      
            return $this->userService->get($id);
        } catch (\Exception $e) {
            $message = 'Error al obtener datos de usuario';            
            $response = $this->controlExceptions(null, $e, $message);            
        }
        return $response;
    }

    function insert(){          
        try {            
            $this->request["password"] = $this->request["document_number"];
            $this->request["confirm_password"]  = $this->request["document_number"];            
            $validator = $this->validator->validate();            
            if($validator->fails()){
                trigger_error("Error de validación", E_USER_ERROR);
            }            
            $userModel = $this->userService->insert($this->request->all());
            $response = response([                
                "message" => "Usuario creado con éxito",
                "data" => $userModel             
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al registrar usuario';
            $response = $this->controlExceptions($validator, $e, $message);            
        }
        return $response;
    }

    function update(int $id){        
        try {            
            $this->model->findOrFail($id);                  
            $this->request["password"] = trim($this->request["password"]);
            $this->request["confirm_password"] = trim($this->request["confirm_password"]);            
            if(!empty($this->request["password"]) || !empty($this->request["confirm_password"])){                
                $profileValidator = $this->profileValidator->validate();
                if($profileValidator->fails())          
                    trigger_error("Error de validación", E_USER_ERROR);
            }            
            $validator = $this->validator->validate();
            if($validator->fails()){                            
                trigger_error("Error de validación", E_USER_ERROR);
            }
            $userModel = $this->userService->update($this->request->all(), $id);
            $response = response([
                "message" => "Usuario actualizado con éxito",
                "data" => $userModel
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al actualizar usuario';           
            $response = $this->controlExceptions((!empty($validator) ? $validator : (!empty($profileValidator) ? $profileValidator : null)), $e, $message);            
        }
        return $response;
    }

    function updateProfile(int $id){
        try {
            $this->model->findOrFail($id); 
            $profileValidator = $this->profileValidator->validate();
            if($profileValidator->fails()){                     
                trigger_error("Error de validación", E_USER_ERROR);                
            }
            $userModel = $this->userService->updateProfile($this->request->all(), $id);
            $response = response([                
                "message" => "Perfil actualizado con éxito",
                "data" => $userModel             
            ], 201);
        } catch (\Exception $e) {            
            $message = 'Error al actualizar perfil';            
            $response = $this->controlExceptions((!empty($profileValidator) ? $profileValidator : null), $e, $message);            
        }
        return $response;
    }

    function delete($id){
        try {     
            $this->model->findOrFail($id);        
            $this->userService->delete($id);            
            $response = response([                
                "message" => "Usuario eliminado con éxito"                           
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al eliminar usuario';
            $response = $this->controlExceptions(null, $e, $message);            
        }
        return $response;
    }
}


