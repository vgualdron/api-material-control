<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\ThirdFBServiceInterface;
    use App\Models\ThirdFB;
    use Illuminate\Support\Facades\Hash;
    
    class ThirdFBServiceImplement implements ThirdFBServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new ThirdFB;
        }    

        function list(){
            return $this->model->get();
        }
    }
?>