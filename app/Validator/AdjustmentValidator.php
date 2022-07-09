<?php
    namespace App\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class AdjustmentValidator{

        private $request;

        public function __construct(Request $request){
            $this->request = $request;
        }

        public function validate(){            
            return Validator::make($this->request->all(), $this->rules(), $this->messages());            
        }

        private function rules(){
            return[
                "type" => "required|min:1",
                "yard" => "required|exists:yard,id",
                "material" => "required|exists:material,id",
                "amount" => "required|max:20",
                "observation" => "max:200"
            ];
        }

        private function messages(){
            return [  
                "type.required" => "El tipo de ajuste el requerido",
                "type.min" => "El tipo debe ser de al menos un caracter",
                "yard.required" => "El patio es requerido",
                "yard.exists" => "El patio seleccionado no existe",
                "material.required" => "El material es requerido",
                "material.exists" => "El material seleccionado no existe",
                "amount.required" => "La cantidad es requerida",
                "amount.max" => "La cantidad ingresada es demasiado alta",
                "observation.max" => "La observacion debe ser de maximo 200 caracteres"
            ];
        }
    }
?>