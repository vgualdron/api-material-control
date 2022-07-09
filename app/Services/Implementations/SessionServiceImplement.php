<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\SessionServiceInterface;
    use Illuminate\Contracts\Auth\Factory as Auth;
    
    class SessionServiceImplement implements SessionServiceInterface{

        private $model;
        private $auth;
        
        function __construct(Auth $auth){
            $this->auth = $auth;
        }    

        function get(){
            $session = $this->auth->user();
            $data['userId'] = $session->id;
            $data['yard'] = $session->yard;            
            return $data;
        }
    }
?>