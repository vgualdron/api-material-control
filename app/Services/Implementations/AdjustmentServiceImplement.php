<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\AdjustmentServiceInterface;
    use Illuminate\Support\Facades\DB;
    use App\Models\Adjustment;
    
    class AdjustmentServiceImplement implements AdjustmentServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new Adjustment;
        }    

        function list(int $perPage, int $page, string $text, int $adjustment){
            
            $adjustmentQuery = $this->model
                            ->from('adjustment as a')
                            ->select('a.id as  id', DB::Raw('IF(a.type = "A", "AUMENTO", "DISMINUCION") as type'), 'a.yard as yard', 'a.material as material', 'y.name as yard_name', 'm.name as material_name', 'amount', DB::Raw('DATE_FORMAT(a.date, "%d/%m/%Y") as date'))
                            ->join('yard as y', 'a.yard', '=', 'y.id')
                            ->join('material as m', 'a.material', '=', 'm.id')
                            ->where('a.id', $adjustment)
                            ->get();
           
           $mainQuery = $this->model
                            ->from('adjustment as a')
                            ->join('yard as y', 'a.yard', '=', 'y.id')
                            ->join('material as m', 'a.material', '=', 'm.id')
                            ->where(function ($query) use ($text){
                                $query->where('m.name', 'like', '%'.$text.'%')
                                    ->orWhere('m.code', 'like', '%'.$text.'%')
                                    ->orWhere('y.name', 'like', '%'.$text.'%')
                                    ->orWhere('y.code', 'like', '%'.$text.'%');
                                }
                            )
                        ->where('a.id', '<>', $adjustment)
                        ->orderBy('a.date', 'DESC')
                        ->paginate($perPage, ['a.id as  id', DB::Raw('IF(a.type = "A", "AUMENTO", "DISMINUCION") as type'), 'a.yard as yard', 'a.material as material', 'y.name as yard_name', 'm.name as material_name', 'amount', DB::Raw('DATE_FORMAT(a.date, "%d/%m/%Y") as date')], 'page', $page);
           
           if(!empty($adjustmentQuery[0]))
                $mainQuery->prepend($adjustmentQuery[0]);
              
            return $mainQuery;
            
        }

        function get(int $id){
            return $this->model->select('id', 'type', 'yard', 'material', 'amount', 'observation', 'date')->where('id', $id)->first();
        }

        function insert(array $adjustment){
            $model = $this->model->create($adjustment);
            return $model;          
        }

        function update(array $adjustment, int $id){  
            $this->model->where('id', $id)->first()
            ->fill($adjustment)->save();
            $model = $this->model->where('id', $id)->first();            
            return $model;
        }

        function delete(int $id){         
            $adjustment = $this->model->find($id);                    
            $adjustment->delete();
        }
    }
?>