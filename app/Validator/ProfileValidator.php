<?php
    namespace App\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class ProfileValidator{

        private $request;

        public function __construct(Request $request){         
            $this->request = $request;
        }

        public function validate(){            
            return Validator::make($this->request->all(), $this->rules(), $this->messages());            
        }

        private function rules(){
            return[                             
                "password" => "required|min:5",
                "confirm_password" => "required|same:password"
            ];
        }

        private function messages(){
            return [  
                "confirm_password.same" => "La contraseña no coincide con la confirmación",                
                "password.min" => "La clave debe tener un mínimo de 5 caracteres",
                "password.required" => "La clave es requerida",
                "confirm_password.required" => "La confirmación de la clave es requerida"
            ];
        }
    }
?>