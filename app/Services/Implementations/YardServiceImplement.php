<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\YardServiceInterface;
    use App\Models\Yard;
    
    class YardServiceImplement implements YardServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new Yard;
        }    

        function list(){
            return $this->model->get();
        }

        function get(int $id){        
            return $this->model->where('id', $id)->first();
        }

        function insert(array $yard){      
            $model = $this->model->create($yard);
            return $model;          
        }

        function update(array $yard, int $id){  
            $this->model->where('id', $id)->first()
            ->fill($yard)->save();
            $model = $this->model->where('id', $id)->first();            
            return $model;
        }

        function delete(int $id){         
            $yard = $this->model->find($id);                    
            $yard->delete();
        }
    }
?>