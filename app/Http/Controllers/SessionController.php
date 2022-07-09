<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\SessionServiceImplement;

class SessionController extends Controller
{
    private $permissionService;

    public function __construct(Request $request, SessionServiceImplement $permissionService){
        $this->request = $request;
        $this->permissionService = $permissionService;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function get(){
        try {
            return $this->permissionService->get();
        } catch (\Exception $e) {
            $message = 'Error al obtener datos de la sesión';            
            $response = $this->controlExceptions(null, $e, '', $message);
        }
        return $response;
    }
}


