<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiquet extends Model
{
    public $table = "tiquet";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'type',
        'operation',
        'user', 
        'origin_yard',
        'destiny_yard',
        'supplier',
        'supplier_name',
        'customer',
        'customer_name',
        'material',
        'material_name',
        'ash_percentage',
        'receipt_number',
        'referral_number',        
        'date',
        'time',
        'license_plate',
        'trailer_number',
        'driver_document',
        'driver_name',
        'gross_weight',
        'tare_weight',
        'net_weight',
        'conveyor_company',
        'conveyor_company_name',
        'observation',
        'seals',
        'local_created_at',
        'round_trip',
        'freight_settlement',
        'material_settlement',
        'material_settlement_retention_percentage',
        'freight_settlement_retention_percentage',
        'material_settlement_royalties',
        'freight_settlement_unit_value',
        'material_settlement_unit_value',
        'material_settlement_net_value',
        'material_settle_receipt_weight',
        'freight_settle_receipt_weight',
        'freight_weight_settled',
        'material_weight_settled',
        'ticketmovid',
        'ticketmov_date',
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
            'type' => $this->type, 
            'operation' => $this->operation, 
            'user' => $this->user, 
            'origin_yard' => $this->origin_yard, 
            'destiny_yard' => $this->destiny_yard, 
            'supplier' => $this->supplier, 
            'supplier_name' => $this->supplier_name, 
            'customer' => $this->customer, 
            'customer_name' => $this->customer_name, 
            'material' => $this->material,
            'ash_percentage' => $this->ash_percentage,
            'material_name' => $this->material_name,
            'receipt_number' => $this->receipt_number, 
            'referral_number' => $this->referral_number,
            'date' => $this->date, 
            'time' => $this->time, 
            'license_plate' => $this->license_plate, 
            'trailer_number' => $this->trailer_number,
            'driver_document' => $this->driver_document,
            'driver_name' => $this->driver_name,
            'gross_weight' => $this->gross_weight, 
            'tare_weight' => $this->tare_weight, 
            'net_weight' => $this->net_weight, 
            'conveyor_company' => $this->conveyor_company, 
            'conveyor_company_name' => $this->conveyor_company_name, 
            'observation' => $this->observation, 
            'seals' => $this->seals, 
            'local_created_at' => $this->local_created_at, 
            'round_trip' => $this->round_trip, 
            'created_at' => $this->created_at, 
            'updated_at' => $this->updated_at
        ];
    }
}
