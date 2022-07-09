<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\ZoneServiceInterface;
    use App\Models\Zone;
    
    class ZoneServiceImplement implements ZoneServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new Zone;
        }    

        function list(int $perPage, int $page, string $text, int $zone){

            $zoneQuery = $this->model->select('id', 'code', 'name')
                            ->where('id', $zone)
                            ->get();
        
           $mainQuery = $this->model->where(function ($query) use ($text){
                                $query->where('name', 'like', '%'.$text.'%')
                                    ->orWhere('code', 'like', '%'.$text.'%');
                                                    }
                            )
                        ->where('id', '<>', (!empty($zoneQuery[0]->id) ? $zoneQuery[0]->id : 0))
                        ->orderBy('code')
                        ->paginate($perPage, ['id', 'name', 'code'], 'page', $page);
           
            if(!empty($zoneQuery[0]))
                $mainQuery->prepend($zoneQuery[0]);
                
            return $mainQuery;
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