<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\ThirdServiceInterface;
    use GuzzleHttp\Client;
    use App\Models\ThirdFB;
    use Illuminate\Support\Facades\DB;
    
    class ThirdServiceImplement implements ThirdServiceInterface{

        private $thirdFB;
        private $client;

        function __construct(){
            $this->thirdFB = new ThirdFB;
            $this->client = new Client(['base_uri' => 'http://201.244.209.139']);
        }
        
        function listByType($type, $start_date, $final_date){
            
            try {
                if($start_date === '' && $final_date === '') {
                    return json_decode($this->client->get('/material-control/third/'.$type.'/'.date('Y'))->getBody()->getContents());
                } else {
                    $arrayThird = [];
                    if($type === 'CONTRATISTA') {
                        $thirds = DB::table('tiquet')->select('conveyor_company', DB::Raw('SUBSTRING_INDEX(conveyor_company_name, "/", 1) as nit'),  DB::Raw('SUBSTRING_INDEX(conveyor_company_name, "/", -1) as name'))
                                                    ->whereNull('freight_settlement')
                                                    ->where('type', '<>', 'R')
                                                    ->whereBetween('date', [$start_date, $final_date])
                                                    ->having('nit', '<>', '0')
                                                    ->having('nit', '<>', '00')
                                                    ->distinct()
                                                    ->get();
                        
                        foreach($thirds as $third) {
                            $temporalArray = [];
                            $temporalArray['id'] = $third->conveyor_company;
                            $temporalArray['nit'] = $third->nit;
                            $temporalArray['name'] = $third->name;
                            $temporalArray['customer'] = 0;
                            $temporalArray['associated'] = 0;
                            $temporalArray['contractor'] = 1;
                            array_push($arrayThird, $temporalArray);
                        }
                    } else if($type === 'ASOCIADO') {
                        $thirds = DB::table('tiquet')->select('supplier', 'supplier_name')
                                                    ->whereNull('material_settlement')
                                                    ->where('type', 'C')
                                                    ->whereBetween('date', [$start_date, $final_date])
                                                    ->distinct()
                                                    ->get();
                        
                        foreach($thirds as $third) {
                            $temporalArray = [];
                            $dataThird = explode('/', $third->supplier_name);
                            $temporalArray['id'] = $third->supplier;
                            $temporalArray['nit'] = $dataThird[0];
                            $temporalArray['name'] = $dataThird[1];
                            $temporalArray['customer'] = 0;
                            $temporalArray['associated'] = 1;
                            $temporalArray['contractor'] = 0;
                            array_push($arrayThird, $temporalArray);
                        }
                    } else if($type === 'CLIENTE') {
                        $thirds = DB::table('tiquet')->select('customer', 'customer_name')
                                                    ->whereNull('material_settlement')
                                                    ->where('type', 'V')
                                                    ->whereBetween('date', [$start_date, $final_date])
                                                    ->distinct()
                                                    ->get();
                        
                        foreach($thirds as $third) {
                            $temporalArray = [];
                            $dataThird = explode('/', $third->customer_name);
                            $temporalArray['id'] = $third->customer;
                            $temporalArray['nit'] = $dataThird[0];
                            $temporalArray['name'] = $dataThird[1];
                            $temporalArray['customer'] = 1;
                            $temporalArray['associated'] = 0;
                            $temporalArray['contractor'] = 0;
                            array_push($arrayThird, $temporalArray);
                        }
                    }
                    return $arrayThird;
                }
                
                
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }
?>