<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\ThirdFBServiceInterface;
    use App\Models\ThirdFB;
    use Illuminate\Support\Facades\Hash;
    
    class ThirdFBServiceImplement implements ThirdFBServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new ThirdFB;
        }    

        function list(){           
            try{
                return $this->model->where('CLIENTE','=', 'S')
                                    ->orWhere('ASOCIADO','=', 'S')
                                    ->orWhere('PROVEED','=', 'S')
                                    ->selectRaw("TERID, CAST(NIT AS VARCHAR(200) CHARACTER SET ISO8859_1) NIT, CAST(NOMBRE AS VARCHAR(200) CHARACTER SET ISO8859_1) AS NOMBRE, IIF(CLIENTE = 'S', 1, 0) AS CLIENTE, IIF(ASOCIADO = 'S', 1, 0) AS ASOCIADO, IIF(PROVEED = 'S', 1, 0) AS PROVEED")
                                    ->get();
            } catch (\Exception $e) {
                echo var_dump($e->getMessage());
            }
        }
    }
?>