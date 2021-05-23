<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\UserServiceImplement;
use App\Validator\UserValidator;

use Spatie\Permission\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    private $userService;
    private $request;
    private $validator;

    public function __construct(UserServiceImplement $userService, Request $request, UserValidator $validator){
        $this->userService = $userService;
        $this->request = $request;
        $this->validator = $validator;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function listUser(){
        return response($this->userService->listUser());
    }

    function insertUser(){      
        /*$response = response("", 201);
        $validator = $this->validator->validate(); 
        if($validator->fails()){                 
            $response = response([
                "status" => 422,
                "message" => "error",
                "error" => $validator->messages()
            ], 422);
        }else{
            $this->userService->insertUser($this->request->all());
        }
        return $response;*/        
        try {
            $validator = $this->validator->validate();
            $userModel = $this->userService->insertUser($this->request->all());
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

    function updateUser(int $id){       
        /*$response = response("", 202);
        $validator = $this->validator->validate(); 
        if($validator->fails()){    
            $response = response([
                "status" => 422,
                "message" => "error",
                "error" => $validator->messages()
            ], 422);
        }else{
            $this->userService->updateUser($this->request->all(), $id);
        }
        return $response;*/


        try {
            $validator = $this->validator->validate();            
            $userModel = $this->userService->updateUser($this->request->all(), $id);
            $response = response([                
                "message" => "Usuario actualizado con éxito",
                "data" => $userModel             
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al actualizar usuario';
            $response = $this->controlExceptions($validator, $e, $message);            
        }
        return $response;
    }

    function deleteUser($id){             
        /*$response = response("", 204);
        $this->userService->deleteUser($id);
        return $response;*/

        try {
            $this->userService->deleteUser($id);
            $response = response([                
                "message" => "Usuario eliminado con éxito"                           
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al eliminar usuario';            
            $response = $this->controlExceptions($validator, $e, $message);            
        }
        return $response;
    }

    /**
     * Crear un Usuario
     * 
     * Endpoint para crear un usuario
     * 
     * @bodyParam first_name string required The first_name of the user. Example: Víctor
     * @bodyParam last_name  string required The last_name of the user. Example: Gualdrón
     * @bodyParam phone      int    required The phone of the user. Example: 1234
     * @bodyParam email      string required The adress of the user. Example: vgualdron@2rides.com.co
     * @bodyParam id_role    int    required The id ot the role of the user. Example: 25
     * @bodyParam id_company int    required The id ot the company of the user. Example: 25
     * 
     * @response {
     *   "data": {
     *      "id": 1,
     *      "first_name": "Víctor",
     *      "last_name": "Gualdrón",
     *      "phone": "300000000",
     *      "email": "vgualdron@2rides.com.co",
     *      "id_role": 1,
     *      "role": {
     *          ...
     *      },
     *      "id_company": 1,
     *      "company": {
     *          "city": {
     *              ...
     *              "country": {
     *                  ...
     *              }
     *          }
     *      }
     *   },
     *   "message": "Usuario creado con éxito."
     * }
     * 
     * @response 405 {
     *  "code": "10405",
     *  "message": "Error en la validación de los datos, verifique que todos los datos esten completos"
     * }
     * 
     * @response 500 {
     *  "code": "10500",
     *  "message": "Error interno del servidor"
     * }
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        //validate incoming request 
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'id_role' => 'required|int',
            'id_company' => 'required|int'
        ]);

        try {

            $plainPassword = $request->input('phone');

            $user = new User;
            $user->firstname = $request->input('first_name');
            $user->lastname = $request->input('last_name');
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            $user->password = app('hash')->make($plainPassword);
            $user->id_company = $request->input('id_company');
            $user->id_role = $request->input('id_role');
            $user->save();

            $result = array(
                'data' => $user,
                'message' => 'Usuario creado con éxito.'
            );

            return response()->json($result, 201);

        } catch (\Exception $e) {
            $result = array(
                'message' => $e->getMessage()
            );
            $code = 500;
        }

        return response()->json($result, $code);
    }
}


