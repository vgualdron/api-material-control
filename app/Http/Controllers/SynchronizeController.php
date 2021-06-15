<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\SynchronizeServiceImplement;

class SynchronizeController extends Controller
{
    private $service;    

    public function __construct(SynchronizeServiceImplement $service){
        $this->service = $service;
    }

    function fromServer(){     
        try{
            return response($this->service->fromServer());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}

