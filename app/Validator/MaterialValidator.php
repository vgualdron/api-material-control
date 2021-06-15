<?php
    namespace App\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class MaterialValidator{

        private $request;

        public function __construct(Request $request){
            $this->request = $request;
        }

        public function validate(){            
            return Validator::make($this->request->all(), $this->rules(), $this->messages());            
        }

        private function rules(){
            return[
                "code" => "required|min:3|max:10|unique:material,code,".$this->request->id,
                "name" => "required|min:5|max:150|unique:material,name,".$this->request->id,
                "unit" => "required"
            ];
        }

        private function messages(){
            return [
                "code.required" => "El código del material es requerido",
                "code.unique" => "El código de material '".$this->request->code."', ya existe",               
                "code.min" => "El código de material debe tener un mínimo de 3 caracteres",
                "code.max" => "El código de material debe tener un máximo de 10 caracteres",
                "name.required" => "El nombre del material es requerido",
                "name.unique" => "El nombre de material '".$this->request->name."', ya existe",               
                "name.min" => "El nombre de material debe tener un mínimo de 5 caracteres",
                "name.max" => "El nombre de material debe tener un máximo de 150 caracteres",
                "unit.required" => "La unidad es requerida"
            ];
        }
    }
?>