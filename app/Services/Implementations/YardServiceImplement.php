<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\YardServiceInterface;
    use App\Models\Yard;
    
    class YardServiceImplement implements YardServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new Yard;
        }    

        function list(int $perPage, int $page, string $text, int $yard, int $excludedYard){
          
            $yardQuery = $this->model->select('*')
                            ->where('id', $yard)
                            ->get();
            
            $mainQuery = $this->model->join('zone as z', 'yard.zone', '=', 'z.id')
                                    ->where(function ($query) use ($text){
                                        $query->where('yard.name', 'like', '%'.$text.'%')
                                            ->orWhere('yard.code', 'like', '%'.$text.'%');
                                            })
                                    ->where('yard.id', '<>', (!empty($yardQuery[0]->id) ? $yardQuery[0]->id : 0))
                                    ->where('yard.id', '<>', $excludedYard)
                                    ->orderBy('yard.code')
                                    ->paginate($perPage, ['yard.*', 'z.name as zoneName'], 'page', $page);

            if(!empty($yardQuery[0]))
                $mainQuery->prepend($yardQuery[0]);
            
            return $mainQuery;    
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