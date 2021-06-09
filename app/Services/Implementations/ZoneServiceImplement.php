<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\ZoneServiceInterface;
    use App\Models\Zone;
    
    class ZoneServiceImplement implements ZoneServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new Zone;
        }    

        function list(){
            return $this->model->get();
        }

        function get(int $id){ 
            return $this->model->where('id', $id)->first();
        }

        function insert(array $zone){      
            $model = $this->model->create($zone);
            return $model;          
        }

        function update(array $zone, int $id){  
            $this->model->where('id', $id)->first()
            ->fill($zone)->save();
            $model = $this->model->where('id', $id)->first();            
            return $model;
        }

        function delete(int $id){         
            $zone = $this->model->find($id);                    
            $zone->delete();
        }
    }
?>