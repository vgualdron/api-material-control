<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\TiquetServiceInterface;
    use App\Models\Tiquet;
    
    class TiquetServiceImplement implements TiquetServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new Tiquet;
        }    

        function list(int $perPage, int $page, string $text){
              return $this->model->join('material as m', 'tiquet.material', '=', 'm.id')
                            ->where('tiquet.referral_number', 'like', '%'.$text.'%')
                            ->orWhere('tiquet.receipt_number', 'like', '%'.$text.'%')
                            ->orWhere('m.name', 'like', '%'.$text.'%')
                            ->orderBy('tiquet.date', 'DESC')
                            ->selectRaw('tiquet.*, m.name as material_name, (CASE tiquet.type WHEN "D" THEN "DESPACHO" WHEN "R" THEN "RECEPCION" WHEN "V" THEN "VENTA" WHEN "C" THEN "COMPRA" WHEN "OC" THEN "OPERACION CON CLIENTE" WHEN "OP" THEN "OPERACION CON PROVEEDOR" END) as type')
                            ->paginate($perPage, [], 'page', $page);
        }

        function get(int $id){
            return $this->model->where('id', $id)->first();
        }

        function insert(array $tiquet){ 
            $model = $this->model->create($tiquet);
            return $model;          
        }

        function update(array $tiquet, int $id){  
            $this->model->where('id', $id)->first()
            ->fill($tiquet)->save();
            $model = $this->model->where('id', $id)->first();            
            return $model;
        }

        function delete(int $id){         
            $tiquet = $this->model->find($id);                    
            $tiquet->delete();
        }
    }
?>