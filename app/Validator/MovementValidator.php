<?php
    namespace App\Validator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class MovementValidator{

        private $request;

        public function __construct(Request $request){
            $this->request = $request;
        }

        public function validate(){   
            $request = array('start_date' => $this->request->start_date, 'final_date' => $this->request->final_date);
            return Validator::make($request, $this->rules(), $this->messages());            
        }

        private function rules(){
            return[
                "start_date" => "required",
                "final_date" => "required|after_or_equal:start_date",
            ];
        }

        private function messages(){
            return [
                "start_date.required" => "La fecha inicial es requerida",
                "final_date.required" => "La fecha final es requerida",
                "final_date.after_or_equal" => "La fecha final debe ser mayor o igual a la fecha inicial"
            ];
        }
    }
?>