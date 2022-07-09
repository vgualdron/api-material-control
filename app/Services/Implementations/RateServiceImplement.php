<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\RateServiceInterface;
    use App\Models\Rate;
    use Illuminate\Support\Facades\DB;
    
    class RateServiceImplement implements RateServiceInterface{
        
        private $model;

        function __construct(){
            $this->model = new Rate;
        }    

        function list(int $perPage, int $page, string $text){
            try {
                $mainQuery = $this->model->leftJoin('yard as oy', 'rate.origin_yard', '=', 'oy.id')
                                        ->leftJoin('yard as dy', 'rate.destiny_yard', '=', 'dy.id')
                                        ->leftJoin('material as m', 'rate.material', '=', 'm.id')
                                        ->where(function ($query) use ($text){
                                            $query->where(DB::raw('COALESCE(oy.name, "")'), 'like', '%'.$text.'%')
                                                ->orWhere(DB::raw('COALESCE(dy.name, "")'), 'like', '%'.$text.'%')
                                                ->orWhere(DB::raw('COALESCE(rate.supplier_name, "")'), 'like', '%'.$text.'%')
                                                ->orWhere(DB::raw('COALESCE(rate.customer_name, "")'), 'like', '%'.$text.'%')
                                                ->orWhere(DB::raw('COALESCE(rate.conveyor_company_name, "")'), 'like', '%'.$text.'%')
                                                ->orWhere(DB::raw('COALESCE(rate.supplier_name, "")'), 'like', '%'.$text.'%');
                                            })
                                        ->selectRaw('rate.id, DATE_FORMAT(rate.start_date, "%d/%m/%Y") as start_date,
                                                    DATE_FORMAT(rate.final_date, "%d/%m/%Y") as final_date,
                                                    rate.conveyor_company as conveyor_company,
                                                    rate.conveyor_company_name as conveyor_company_name,
                                                    rate.material as material,
                                                    m.name as material_name,
                                                    rate.material_price as material_price,
                                                    rate.freight_price as freight_price,
                                                    rate.net_price as net_price,
                                                    rate.round_trip <> 0 as round_trip,
                                                    rate.origin_yard as origin_yard,
                                                    rate.destiny_yard as destiny_yard,
                                                    oy.name as origin_yard_name,
                                                    dy.name as destiny_yard_name,
                                                    (CASE rate.movement WHEN "C" THEN rate.supplier_name ELSE oy.name END) as origin_yard_or_supplier_name,
                                                    (CASE rate.movement WHEN "V" THEN rate.customer_name ELSE dy.name END) as destiny_yard_or_customer_name,
                                                    (CASE rate.movement WHEN "T" THEN "TRASLADO" WHEN "C" THEN "COMPRA" WHEN "V" THEN "VENTA" END) as movement')
                                        ->orderBy('rate.start_date')
                                        ->paginate($perPage, [], 'page', $page);

                return $mainQuery;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        function get(int $id){
            return $this->model->where('id', $id)->first();
        }

        function insert(array $rate){ 
            $model = $this->model->create($rate);
            return $model;          
        }

        function update(array $rate, int $id){  
            $this->model->where('id', $id)->first()
            ->fill($rate)->save();
            $model = $this->model->where('id', $id)->first();            
            return $model;
        }

        function delete(int $id){         
            $rate = $this->model->find($id);                    
            $rate->delete();
        }
    }
?>