<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\MaterialServiceInterface;
    use App\Models\Material;
    
    class MaterialServiceImplement implements MaterialServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new Material;
        }    

        function list(){
            return $this->model->get();
        }

        function get(int $id){
            return $this->model->where('id', $id)->first();
        }

        function insert(array $material){      
            $model = $this->model->create($material);
            return $model;          
        }

        function update(array $material, int $id){  
            $this->model->where('id', $id)->first()
            ->fill($material)->save();
            $model = $this->model->where('id', $id)->first();            
            return $model;
        }

        function delete(int $id){         
            $material = $this->model->find($id);                    
            $material->delete();
        }
    }
?>