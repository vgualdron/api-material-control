<?php
    namespace App\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class RateValidator{

        private $request;

        public function __construct(Request $request){
            $this->request = $request;
        }

        public function validate(){            
            return Validator::make($this->request->all(), $this->rules(), $this->messages());            
        }

        private function rules(){
            return[
                "movement" => "required|min:1|max:2",
                "origin_yard" => "nullable|exists:yard,id",
                "destiny_yard" => "nullable|exists:yard,id",
                "material" => "exists:material,id",
                "start_date" => "required",
                "final_date" => "required|after_or_equal:start_date",
                "freight_price" => "required"
            ];
        }

        private function messages(){
            return [
                "movement.required" => "El tipo de movimiento de tarifa es requerido",
                "movement.min" => "El tipo de movimiento debe tener un minimo de 1 caracter",
                "movement.max" => "El tipo de movimiento debe tener un máximo de 2 caracteres",
                "origin_yard.exists" => "El patio de origen seleccionado no existe",
                "destiny_yard.exists" => "El patio de destino seleccionado no existe",
                "material.exists" => "El material seleccionado no existe",
                "freight_price.required" => "El precio es requerido",
                "start_date.required" => "La fecha inicial es requerida",
                "final_date.required" => "La fecha final es requerida",
                "final_date.after_or_equal" => "La fecha final debe ser mayor o igual a la fecha inicial"
            ];
        }
    }
?>