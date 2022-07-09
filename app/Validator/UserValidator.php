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
                'document_number' => 'required|min:5|unique:user,document_number,'.$this->request->id,
                'name' => 'required',
                'phone' => 'required',
                'yard' => 'nullable|exists:yard,id'
            ];
        }

        private function messages(){
            return [
                'document_number.unique' => 'El número de documento "'.$this->request->document_number.'" ya se encuentra registrado',
                'name.required' => 'El nombre es requerido',
                'phone.required' => 'El número de teléfono es requerido',
                'document_number.required' => 'El número de documento es requerido',
                'document_number.min' => 'El número de documento debe tener como mínimo 5 caracteres',
                "yard.exists" => "El patio seleccionado no existe"
            ];
        }
    }
?>