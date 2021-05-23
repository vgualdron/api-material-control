<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\ThirdFBServiceImplement;

class ThirdFBController extends Controller
{
    private $service;    

    public function __construct(ThirdFBServiceImplement $service){
        $this->service = $service;
    }

    function list(){     
        return response($this->service->list());
    }
}
