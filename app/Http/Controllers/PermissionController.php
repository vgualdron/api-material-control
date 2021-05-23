<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\PermissionServiceImplement;
use Illuminate\Contracts\Auth\Factory as Auth;


class PermissionController extends Controller
{
    private $permissionService;
    protected $auth;  
    
    public function __construct(PermissionServiceImplement $permissionService, Auth $auth){
        $this->permissionService = $permissionService;
        $this->auth = $auth;        
    }   

    function listBySession(){  
        return response($this->permissionService->listBySession($this->auth->user())); 
    }

    function listBySessionGroup(){             
        return response($this->permissionService->listBySessionGroup($this->auth->user()));
    }
}
