<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\ThirdServiceImplement;

class ThirdController extends Controller
{
   
    private $thirdService;

    public function __construct(ThirdServiceImplement $thirdService){      
        $this->thirdService = $thirdService;
    }

    function listByType($type, $start_date, $final_date){
        $start_date = trim(urldecode($start_date));
        $final_date = trim(urldecode($final_date));
        return response($this->thirdService->listByType($type, $start_date, $final_date));
    }
}


