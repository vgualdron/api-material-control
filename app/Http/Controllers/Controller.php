<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function controlExceptions($validator, $exception, $message = 'Se ha presentado una excepción'){
        $errors = array();       
        $code = $exception->getTrace()[0]['function']=='findOrFail' ? 404 : 500;
        $message = $exception->getTrace()[0]['function']=='findOrFail' ? 'El registro seleccionado no existe' : $message;        
        if($validator!=null&&$validator->fails()){            
            $errors = $validator->messages();
        }
        else{  
            $errors = array(
                'message' => array(
                    $exception->getMessage()
                )
            );  
        }
        $response = response([           
            "message" => $message,
            "errors" => $errors
        ], $code);
        return $response;
    }
}
