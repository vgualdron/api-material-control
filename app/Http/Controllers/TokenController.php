<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\TokenServiceImplement;

class TokenController extends Controller
{
    private $service;    

    public function __construct(TokenServiceImplement $service){
        $this->service = $service;
    }

    function getActiveToken(){
        return response($this->service->getActiveToken()[0]);
    }
}
