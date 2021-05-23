<?php
    namespace App\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class UserValidator{

        private $request;

        public function __construct(Request $request){
            $this->request = $request;
        }

        public function validate(){            
            return Validator::make($this->request->all(), $this->rules(), $this->messages());            
        }

        private function rules(){
            return[
                "name" => "required",
                "document_number" => "required|unique:user,document_number,".$this->request->id,                
                "password" => "required",
                "confirm_password" => "required|same:password",
                "phone" => "required"
            ];
        }

        private function messages(){
            return [  
                "confirm_password.same" => "La contraseña no coincide con la confirmación",
                "document_number.unique" => "El número de documento ya existe"
            ];
        }
    }
?>