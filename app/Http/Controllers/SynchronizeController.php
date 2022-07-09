<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\SynchronizeServiceImplement;

class SynchronizeController extends Controller
{
    private $service;
    private $request;
    protected $auth;
    public function __construct(SynchronizeServiceImplement $service, Request $request){
        $this->service = $service;
        $this->request = $request;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function toServer(){     
        try{
            $success = response($this->service->toServer($this->request->all()));
            if($success->original){
                $response = response([                
                    "message" => "Los datos del tiquet se han enviado correctamente al servidor",
                    "data" => []            
                ], 201);
            } else {
                trigger_error("Error al enviar datos de tiquet al servidor", E_USER_ERROR);
            }
        } catch (\Exception $e) {
            $message = 'Error al enviar datos de tiquet al servidor';
            $response = $this->controlExceptions(null, $e, '', $message);
        }
        return $response;
    }
    
    function fromServer(){
        try{
            $success = response($this->service->fromServer());
            if(!empty($success->original)) {
                 $response = response([                
                    "message" => "Los datos del tiquet se han recuperado exitasamente desde el servidor",
                    "data" => $success->original
                ], 201);
            } else {
                trigger_error("No se han encontrado datos, es posible que se presenten inconvenientes en la conexión con el servidor de Novum", E_USER_ERROR);
            }
        } catch (\Exception $e) {
            $message = 'Error al recuperar datos desde el servidor';
            $response = $this->controlExceptions(null, $e, '', $message);
        }
        return $response;
    }
}

