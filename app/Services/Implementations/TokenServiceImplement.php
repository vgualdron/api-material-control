<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\TokenServiceInterface;
    use App\Models\Token;
    use Illuminate\Support\Facades\Hash;
    
    class TokenServiceImplement implements TokenServiceInterface{
        
        private $model;    

        function __construct(){            
            $this->model = new Token;
        }    

        function getActiveToken(){
            return $this->model->where('password_client', 1)->where('revoked', 0)->get();
        }
    }
?>