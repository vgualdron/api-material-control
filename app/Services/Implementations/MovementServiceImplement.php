<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\MovementServiceInterface;
    use GuzzleHttp\Client;
    use Illuminate\Support\Facades\DB;
    
    class MovementServiceImplement implements MovementServiceInterface{
        
        private $client;

        function __construct(){
            $this->client = new Client(['base_uri' => 'http://201.244.209.139']);
        }    

        function get(string $start_date, string $final_date){ 
            
           try {
                $dates = [$start_date, $final_date];
                $tiquetsTransfers = DB::table('tiquet as t1')
                                        ->select('oz.code as PREFIJO', 'oy.code as CCOSTO', 't1.referral_number as NUMERO', DB::Raw('DATE_FORMAT(t1.date, "%d/%m/%Y") as FECHA'),
                                            DB::Raw('"00" as ORIGEN'), DB::Raw('"00" as DESTINO'), 't1.license_plate as PLACA',  DB::Raw('CONCAT(oz.code, oy.code, om.code) as ART1'), 
                                            DB::Raw('"00" as BODEGA'), DB::Raw('CONCAT(dz.code,dy.code,dm.code) as ART2'), DB::Raw('"00" as BODEGA2'), DB::Raw('FORMAT(t1.gross_weight, 2) as BRUTO'),
                                            't1.tare_weight as TARA', DB::Raw('IF(om.unit <> "U", t1.net_weight, 1) as NETO'), DB::Raw('0 as TARIFAC'),
                                            DB::Raw('FORMAT(t1.freight_settlement_unit_value, 2) as TARIFAT'),  DB::Raw('"T" as TIPOES'), DB::Raw('COALESCE(t1.observation,"   ",t2.observation) as OBS'),
                                            DB::Raw('SUBSTRING_INDEX(t1.conveyor_company_name, "/", 1) as NITTRANS'), 't1.id as TICKET')
                                        ->join('tiquet as t2', function($join){
                                            $join->on('t1.referral_number', '=', 't2.referral_number');
                                            $join->on('t2.type','=',DB::raw('"R"'));
                                        })
                                        ->join('yard as oy', 't1.origin_yard', '=', 'oy.id')
                                        ->join('zone as oz', 'oy.zone', '=', 'oz.id')
                                        ->join('yard as dy', 't1.destiny_yard', '=', 'dy.id')
                                        ->join('zone as dz', 'dy.zone', '=', 'dz.id')
                                        ->join('material as om', 't1.material', '=', 'om.id')
                                        ->join('material as dm', 't2.material', '=', 'dm.id')
                                        ->where('t1.type', '=', 'D')
                                        ->whereNotNull('t1.freight_settlement')
                                        ->whereBetween('t1.date', $dates)
                                        ->whereNull('t1.ticketmovid');
                                        
                $tiquetsSalesPurchases = DB::table('tiquet as t')
                                        ->select(DB::Raw('IF(type = "C", dz.code, oz.code) as PREFIJO'), DB::Raw('IF(type = "C", dy.code, oy.code) as CCOSTO'),  DB::Raw('IF(t.type = "V", t.referral_number, t.receipt_number) as NUMERO'),
                                            DB::Raw('DATE_FORMAT(t.date, "%d/%m/%Y") as FECHA'), DB::Raw('IF(t.type = "C", SUBSTRING_INDEX(t.supplier_name, "/", 1),"00") as ORIGEN'), DB::Raw('IF(t.type = "V", SUBSTRING_INDEX(t.customer_name, "/", 1),"00") as DESTINO'),
                                            't.license_plate as PLACA', DB::Raw('CONCAT(IF(type = "C", dz.code, oz.code), IF(type = "C", dy.code, oy.code), m.code) as ART1'), DB::Raw('"00" as BODEGA'), DB::Raw('"00" as ART2'), DB::Raw('"00" as BODEGA2'),
                                            DB::Raw('FORMAT(t.gross_weight, 2) as BRUTO'), DB::Raw('FORMAT(t.tare_weight, 2) as TARA'), DB::Raw('IF(m.unit <> "U", FORMAT(t.net_weight, 2), 1) as NETO'), DB::Raw('FORMAT(t.material_settlement_unit_value, 2) as TARIFAC'),
                                            DB::Raw('FORMAT(COALESCE(t.freight_settlement_unit_value, 0), 2) as TARIFAT'), DB::Raw('IF(type = "C", "E", "S") as TIPOES'), DB::Raw('t.observation as OBS'), DB::Raw('SUBSTRING_INDEX(t.conveyor_company_name, "/", 1) as NITTRANS'), 't.id as TICKET')
                                        ->leftJoin('yard as oy', 't.origin_yard', '=', 'oy.id')
                                        ->leftJoin('zone as oz', 'oy.zone', '=', 'oz.id')
                                        ->leftJoin('yard as dy', 't.destiny_yard', '=', 'dy.id')
                                        ->leftJoin('zone as dz', 'dy.zone', '=', 'dz.id')
                                        ->join('material as m', 't.material', '=', 'm.id')
                                        ->where(function ($query) {
                                            $query->where('t.type', '=', 'C')
                                                ->orWhere('t.type', '=', 'V');
                                        })
                                        //->whereNotNull('t.freight_settlement')
                                        ->whereRaw('IF(SUBSTRING_INDEX(t.conveyor_company_name, "/", 1) <> "00", t.freight_settlement IS NOT NULL,TRUE)')
                                        ->whereNotNull('t.material_settlement')
                                        ->whereBetween('t.date', $dates)
                                        ->whereNull('t.ticketmovid');
                
                $tiquets = $tiquetsTransfers->union($tiquetsSalesPurchases)->get();
                
                return $tiquets;
                
            } catch (\Exception $e) {
                echo $e->getMessage().'   '.$e->getLine();
            }
        }
        
        function generate(array $data) {
            try {
            if(count($data) > 0) {
                $movements = $data['movements'];
                $response = $this->client->post('/material-control/movement/generate/'.date('Y'), ['json' => ['data' => $movements]])->getBody()->getContents();
                $response = json_decode($response);
                foreach ($response as $item) {
                    DB::table('tiquet')->where('id', $item->TICKET)->update(Array('ticketmovid' => $item->TICKETMOVID, 'ticketmov_date' => date("Y-m-d")));  
                }
                return $response;
            } else {
                return [];
            }
        } catch (\Exception $e) {
                echo $e->getMessage().'   '.$e->getLine();
            }
        }
    }
?>