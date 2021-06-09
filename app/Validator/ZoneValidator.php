<?php
    namespace App\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class ZoneValidator{

        private $request;

        public function __construct(Request $request){
            $this->request = $request;
        }

        public function validate(){            
            return Validator::make($this->request->all(), $this->rules(), $this->messages());            
        }

        private function rules(){
            return[
                "code" => "required|min:3|max:10|unique:zone,code,".$this->request->id,
                "name" => "required|min:5|max:30|unique:zone,name,".$this->request->id
            ];
        }

        private function messages(){
            return [
                "code.required" => "El código de la zona es requerido",
                "code.unique" => "El código de zona '".$this->request->code."', ya existe",               
                "code.min" => "El código de zona debe tener un mínimo de 3 caracteres",
                "code.max" => "El código de zona debe tener un máximo de 10 caracteres",
                "name.required" => "El nombre de la zona es requerido",
                "name.unique" => "El nombre de zona '".$this->request->name."', ya existe",               
                "name.min" => "El nombre de zona debe tener un mínimo de 5 caracteres",
                "name.max" => "El nombre de zona debe tener un máximo de 30 caracteres"
            ];
        }
    }
?>