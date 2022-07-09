<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function controlExceptions($validator, $exception, $table = '', $message = 'Se ha presentado una excepción', $messageDuplicate = ''){
        $errors = array();       
        $code = !empty($exception) && $exception->getTrace()[0]['function']=='findOrFail' ? 404 : 500;
        $message = !empty($exception) && $exception->getTrace()[0]['function']=='findOrFail' ? 'El registro seleccionado no existe' : $message;
        if($validator!=null && $validator->fails()){            
            $errors = $validator->messages();
        }
        else if(!empty($messageDuplicate)){
          $errors = array(
                'message' => array(
                    $messageDuplicate
                )
            );  
        }
        else{  
            $errors = array(
                'message' => array(
                    $exception->getCode() != '23000' ? $exception->getMessage() : ($table.' tiene registros asociados, no se puede eliminar')
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
