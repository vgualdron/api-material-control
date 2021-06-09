<?php
    namespace App\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class YardValidator{

        private $request;

        public function __construct(Request $request){
            $this->request = $request;
        }

        public function validate(){            
            return Validator::make($this->request->all(), $this->rules(), $this->messages());            
        }

        private function rules(){
            return[
                "name" => "required|min:5|unique:yard,name,".$this->request->id,
                "code" => "required|min:5|unique:yard,code,".$this->request->id,
                "zone" => "required|exists:zone,id"
            ];
        }

        private function messages(){
            return [  
                "name.required" => "El nombre del patio es requerido",
                "name.unique" => "El nombre de patio '".$this->request->name."', ya existe",               
                "name.min" => "El nombre de patio debe tener un minimo de 5 caracteres",
                "code.required" => "El código del patio es requerido",
                "code.unique" => "El código de patio '".$this->request->code."', ya existe",               
                "code.min" => "El codigo de patio debe tener un minimo de 5 caracteres",
                "zone.required" => "Debe ingresar una zona",
                "zone.exists" => "La zona seleccionada no existe"
            ];
        }
    }
?>