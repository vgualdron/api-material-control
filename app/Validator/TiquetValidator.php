<?php
    namespace App\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class TiquetValidator{

        private $request;

        public function __construct(Request $request){
            $this->request = $request;
        }

        public function validate(){            
            return Validator::make($this->request->all(), $this->rules(), $this->messages());            
        }

        private function rules(){
            return[
                'type' => 'required',
                'date' => 'required',
                'time' => 'required',
                'material' => 'required',
                'conveyor_company' => 'required',
                'driver_document' => 'required',
                'driver_name' => 'required',
                'license_plate' => 'required'
            ];
        }

        private function messages(){
            return [
                "type.required" => "El tipo de movimiento requerido",
                "date.required" => "La fecha es requerida",
                "time.required" => "La hora es requerida",
                "material.required" => "El material es requerido",
                "conveyor_company.required" => "La empresa transportadora es requerida",
                "driver_document.required" => "El documento del conductor es requerido",
                "driver_name.required" => "El nombre del conductor es requerido",
                "license_plate.required" => "La placa de vehículo es requerido"
            ];
        }
    }
?>