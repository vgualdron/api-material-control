<?php
    namespace App\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class RoleValidator{

        private $request;

        public function __construct(Request $request){
            $this->request = $request;
        }

        public function validate(){            
            return Validator::make($this->request->all(), $this->rules(), $this->messages());            
        }

        private function rules(){
            return[
                "name" => "required|min:5|unique:roles,name,".$this->request->id
            ];
        }

        private function messages(){
            return [  
                "name.required" => "El nombre del rol es requerido",
                "name.unique" => "El nombre de rol '".$this->request->name."', ya existe",               
                "name.min" => "El nombre del patio debe tener un minimo de 4 caracteres"
            ];
        }
    }
?>