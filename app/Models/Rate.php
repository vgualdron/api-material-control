<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    public $table = "rate";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'movement',
        'origin_yard',
        'origin_yard_or_supplier_name',
        'destiny_yard',
        'destiny_yard_or_customer_name',
        'supplier',
        'supplier_name',
        'customer',
        'customer_name',
        'conveyor_company',
        'conveyor_company_name',
        'start_date',
        'final_date',
        'material',
        'material_name',
        'material_price',
        'freight_price',
        'net_price',
        'observation',
        'round_trip',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    function toArray(){
        return [
            'id' => $this->id,
            'movement' => $this->movement,
            'originYard' => $this->origin_yard,
            'originYardOrSupplierName' => $this->origin_yard_or_supplier_name,
            'destinyYard' => $this->destiny_yard,
            'destinyYardOrCustomerName' => $this->destiny_yard_or_customer_name,
            'supplier' => $this->supplier,
            'supplierName' => $this->supplier_name,
            'customer' => $this->customer,
            'customerName' => $this->customer_name,
            'conveyorCompany' => $this->conveyor_company,
            'conveyorCompanyName' => $this->conveyor_company_name,            
            'startDate' => $this->start_date,
            'finalDate' => $this->final_date,            
            'material' => $this->material,
            'materialName' => $this->material_name,
            'materialPrice' => $this->material_price,
            'freightPrice' => $this->freight_price,
            'netPrice' => $this->net_price,
            'observation' => $this->observation,
            'roundTrip' => $this->round_trip
        ];
    }
}
