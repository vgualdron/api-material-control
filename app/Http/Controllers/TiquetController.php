<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\TiquetServiceImplement;
use App\Validator\TiquetValidator;
use App\Models\Tiquet;

class TiquetController extends Controller
{
   
    private $tiquetService;
    private $request;
    private $validator;  
    private $model;

    public function __construct(TiquetServiceImplement $tiquetService, Request $request, TiquetValidator $validator){
        $this->tiquetService = $tiquetService;
        $this->model = new Tiquet;        
        $this->request = $request;
        $this->validator = $validator;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function list($perPage, $page, $text){
        $text = trim(urldecode($text));
        return response($this->tiquetService->list($perPage, $page, $text));
    }
    
    function get($id){
        try {  
            $this->model->findOrFail($id);
            return $this->tiquetService->get($id);
        } catch (\Exception $e) {
            $message = 'Error al obtener datos de tiquet';            
            $response = $this->controlExceptions(null, $e, '', $message);            
        }
        return $response;
    }
    
    function insert(){
        try {
            $request = $this->request->all();
            $type = $request['type'];
            $referral_number = $request['referral_number'];
            $receipt_number = $request['receipt_number'];
            $operation = $request['operation'];
            $tiquet = $this->model->whereRaw('CASE 
                                                WHEN "'.$type.'" = "D" THEN referral_number = "'.$referral_number.'" AND type <> "R"
                                                WHEN "'.$type.'" = "V" OR (("'.$type.'" = "OC" OR "'.$type.'" = "OP") AND "'.$operation.'" = "P") THEN referral_number = "'.$referral_number.'"
                                                WHEN "'.$type.'" = "C" OR "'.$type.'" = "R" OR (("'.$type.'" = "OC" OR "'.$type.'" = "OP") AND "'.$operation.'" = "D") THEN receipt_number = "'.$receipt_number.'"
                                            END')
                                        ->selectRaw("*")
                                        ->get();
            
            if(count($tiquet) > 0){
                $message = 'Error al registrar tiquet';
                $messageDuplicate = 'Ya existe un tiquet con el número de '.($type == 'D' || $type == 'V' || (($type == "OC" || $type == "OP") && $operation == 'P') ? 'remisión' : 'recibo').' ingresado';
                $response = $this->controlExceptions(null, null, '', $message, $messageDuplicate);
            } else {
                $validator = $this->validator->validate();            
                if($validator->fails()){               
                    trigger_error("Error de validación", E_USER_ERROR);             
                }         
                $tiquetModel = $this->tiquetService->insert($request);
                $response = response([                
                    "message" => "Tiquet creado con éxito",
                    "data" => $tiquetModel             
                ], 201);
            }
        } catch (\Exception $e) {
            $message = 'Error al registrar tiquet';
            $response = $this->controlExceptions($validator, $e, '', $message);            
        }
        return $response;
    }

    function update(int $id){        
        try {
            $request = $this->request->all();
            $type = $request['type'];
            $referral_number = $request['referral_number'];
            $receipt_number = $request['receipt_number'];
            $operation = $request['operation'];
            $tiquet = $this->model->whereRaw('CASE 
                                                WHEN "'.$type.'" = "D" THEN referral_number = "'.$referral_number.'" AND type <> "R"
                                                WHEN "'.$type.'" = "V" OR (("'.$type.'" = "OC" OR "'.$type.'" = "OP") AND "'.$operation.'" = "P") THEN referral_number = "'.$referral_number.'"
                                                WHEN "'.$type.'" = "C" OR "'.$type.'" = "R" OR (("'.$type.'" = "OC" OR "'.$type.'" = "OP") AND "'.$operation.'" = "D") THEN receipt_number = "'.$receipt_number.'"
                                            END AND id <> '.$id)
                                        ->selectRaw("*")
                                        ->get();
            if(count($tiquet) > 0){
                $message = 'Error al actualizar tiquet';
                $messageDuplicate = 'Ya existe un tiquet con el número de '.($type == 'D' || $type == 'V' || (($type == "OC" || $type == "OP") && $operation == 'P') ? 'remisión' : 'recibo').' ingresado';
                $response = $this->controlExceptions(null, null, '', $message, $messageDuplicate);
            } else {
                $this->model->findOrFail($id);
                $validator = $this->validator->validate();
                if($validator->fails()){                            
                    trigger_error("Error de validación", E_USER_ERROR);
                }
                $tiquetModel = $this->tiquetService->update($this->request->all(), $id);
                $response = response([
                    "message" => "Tiquet actualizado con éxito",
                    "data" => $tiquetModel
                ], 201);
            }
        } catch (\Exception $e) {
            $message = 'Error al actualizar tiquet';
            $response = $this->controlExceptions((!empty($validator) ? $validator : null), $e, '', $message);
            
        }
        return $response;
    }

    function delete($id){
        try {     
            $this->model->findOrFail($id);        
            $this->tiquetService->delete($id);            
            $response = response([                
                "message" => "Tiquet eliminado con éxito"                           
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al eliminar tiquet';
            $response = $this->controlExceptions(null, $e, 'El tiquet', $message);            
        }
        return $response;
    }
}


