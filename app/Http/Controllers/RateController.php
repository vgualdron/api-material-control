<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\Implementations\RateServiceImplement;
use App\Validator\RateValidator;
use App\Models\Rate;

class RateController extends Controller
{
   
    private $rateService;
    private $request;
    private $validator;  
    private $model;

    public function __construct(RateServiceImplement $rateService, Request $request, RateValidator $validator){      
        $this->rateService = $rateService;
        $this->model = new Rate;        
        $this->request = $request;
        $this->validator = $validator;
        $this->middleware('validate', ['except' => []
        ]);
    }

    function list($perPage, $page, $text){
        $text = trim(urldecode($text));
        return response($this->rateService->list($perPage, $page, $text));
    }

    function get($id){
        try {  
            $this->model->findOrFail($id);
            return $this->rateService->get($id);
        } catch (\Exception $e) {
            $message = 'Error al obtener datos de tarifa';            
            $response = $this->controlExceptions(null, $e, '', $message);            
        }
        return $response;
    }

    function insert(){          
        try { 
            $validator = $this->validator->validate();            
            if($validator->fails()){               
                trigger_error("Error de validación", E_USER_ERROR);             
            }
            $movement = $this->request->movement;
            $start_date = $this->request->start_date;
            $final_date = $this->request->final_date;
            $material = $this->request->material;
            $origin_yard = $this->request->origin_yard;
            $destiny_yard = $this->request->destiny_yard;
            $supplier_name = $this->request->supplier_name;
            $customer_name = $this->request->customer_name;
            $conveyor_company_name = $this->request->conveyor_company_name;
            $round_trip = $this->request->round_trip;
            $rate = $this->model->where('movement', '=', $movement)
                                ->where('material', '=', $material)
                                ->where('origin_yard', '=', $origin_yard)
                                ->where('destiny_yard', '=', $destiny_yard)
                                ->where('supplier_name', '=', $supplier_name)
                                ->where('customer_name', '=', $customer_name)
                                ->where('conveyor_company_name', '=', $conveyor_company_name)
                                ->where('round_trip', '=', $round_trip)
                                ->where(function ($query) use ($start_date, $final_date){
                                        $query->whereRaw('? BETWEEN start_date AND final_date', $start_date)
                                            ->orWhereRaw('? BETWEEN start_date AND final_date', $final_date)
                                            ->orWhereRaw('start_date >= ? AND final_date <= ?', [$start_date, $final_date]);
                                })
                                ->selectRaw("*")
                                ->get();
            if(count($rate) > 0){
                $message = 'Error al registrar tarifa';
                $messageDuplicate = 'Ya existe una tarifa con las caracteristicas ingresadas';
                $response = $this->controlExceptions(null, null, '', $message, $messageDuplicate);
            } else {
                $rateModel = $this->rateService->insert($this->request->all());
                $response = response([                
                    "message" => "Tarifa creada con éxito",
                    "data" => $rateModel             
                ], 201);
            }
        } catch (\Exception $e) {
            $message = 'Error al registrar tarifa';
            $response = $this->controlExceptions($validator, $e, '', $message);            
        }
        return $response;
    }

    function update(int $id){        
        try {            
            $this->model->findOrFail($id);
            $validator = $this->validator->validate();
            if($validator->fails()){                            
                trigger_error("Error de validación", E_USER_ERROR);
            }
            $movement = $this->request->movement;
            $start_date = $this->request->start_date;
            $final_date = $this->request->final_date;
            $material = $this->request->material;
            $origin_yard = $this->request->origin_yard;
            $destiny_yard = $this->request->destiny_yard;
            $supplier_name = $this->request->supplier_name;
            $customer_name = $this->request->customer_name;
            $conveyor_company_name = $this->request->conveyor_company_name;
            $round_trip = $this->request->round_trip;
            $rate = $this->model->where('movement', '=', $movement)
                                ->where('material', '=', $material)
                                ->where('origin_yard', '=', $origin_yard)
                                ->where('destiny_yard', '=', $destiny_yard)
                                ->where('supplier_name', '=', $supplier_name)
                                ->where('customer_name', '=', $customer_name)
                                ->where('conveyor_company_name', '=', $conveyor_company_name)
                                ->where('round_trip', '=', $round_trip)
                                ->where(function ($query) use ($start_date, $final_date){
                                        $query->whereRaw('? BETWEEN start_date AND final_date', $start_date)
                                            ->orWhereRaw('? BETWEEN start_date AND final_date', $final_date);
                                })
                                ->where('id', '<>', $id)
                                ->selectRaw("*")
                                ->get();
            if(count($rate) > 0){
                $message = 'Error al registrar tarifa';
                $messageDuplicate = 'Ya existe una tarifa con las caracteristicas ingresadas';
                $response = $this->controlExceptions(null, null, '', $message, $messageDuplicate);
            } else {
                $rateModel = $this->rateService->update($this->request->all(), $id);
                $response = response([
                    "message" => "Tarifa actualizada con éxito",
                    "data" => $rateModel
                ], 201);
            }
        } catch (\Exception $e) {
            $message = 'Error al actualizar tarifa';
            $response = $this->controlExceptions((!empty($validator) ? $validator : null), $e, '', $message);
            
        }
        return $response;
    }

    function delete($id){
        try {     
            $this->model->findOrFail($id);        
            $this->rateService->delete($id);            
            $response = response([                
                "message" => "Tarifa eliminada con éxito"                           
            ], 201);
        } catch (\Exception $e) {
            $message = 'Error al eliminar tarifa';
            $response = $this->controlExceptions(null, $e, 'La tarifa', $message);            
        }
        return $response;
    }
}


