<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\MaterialServiceInterface;
    use App\Models\Material;
    
    class MaterialServiceImplement implements MaterialServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new Material;
        }    

        function list(int $perPage, int $page, string $text, int $material){
            
            $materialQuery = $this->model->select('id', 'code', 'name')
                            ->where('id', $material)
                            ->get();
            
            $mainQuery = $this->model->where(function ($query) use ($text){
                                $query->where('name', 'like', '%'.$text.'%')
                                    ->orWhere('code', 'like', '%'.$text.'%');
                                                    }
                            )
                        ->where('id', '<>', (!empty($materialQuery[0]->id) ? $materialQuery[0]->id : 0))
                        ->orderBy('code')
                        ->paginate($perPage, ['id', 'name', 'code', 'unit'], 'page', $page);
            
            if(!empty($materialQuery[0]))
                $mainQuery->prepend($materialQuery[0]);
                
            return $mainQuery;
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