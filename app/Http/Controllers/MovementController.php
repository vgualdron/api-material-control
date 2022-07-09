<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\MovementServiceImplement;
    use App\Validator\MovementValidator;

class MovementController extends Controller
{
   
    private $service;
    private $request;
    private $validator;

    public function __construct(MovementServiceImplement $service, MovementValidator $validator, Request $request){      
        $this->service = $service;      
        $this->request = $request;
        $this->validator = $validator;
        $this->middleware('validate', ['except' => []
        ]);
    }
    
    function get(string $start_date, string $final_date){
        try {
            $validator = $this->validator->validate();            
            if($validator->fails()){               
                trigger_error("Error de validación", E_USER_ERROR);             
            }
            return $this->service->get($start_date, $final_date);
        } catch (\Exception $e) {
            $message = 'Error al generar movimientos';
            $response = $this->controlExceptions($validator, $e, '', $message);
        }
        return $response;
    }
    
    function generate () {
        $generateds = $this->service->generate($this->request->all());
        if(count($generateds) > 0) {
            $response = response([                
                "message" => "Movimientos generados con éxito",
                "data" => $generateds             
            ], 201);
        } else {
            $response = response([                
                "message" => "No se han generado movimientos",
                "data" => []            
            ], 500);
        }
        return $response;
    }
}


