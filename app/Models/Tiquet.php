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
        'origin_yard',
        'destiny_yard',
        'supplier',
        'client',
        'material',
        'receipt_number',
        'reference_number',
        'start_date',
        'final_date',
        'license_plate',
        'trailer_number',
        'driver',
        'origin_gross_weight',
        'origin_tare_weight',
        'origin_net_weight',
        'destiny_gross_weight',
        'destiny_tare_weight',
        'destiny_net_weight',
        'transportation_company',
        'origin_observation',
        'destiny_observation',
        'origin_seal',
        'destiny_seal',
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
        'password',
    ];

    function toArray(){
        return [
            'id' => $this->id,  
            'type' => $this->type,        
            'originYard' => $this->origin_yard,
            'destinyYard' => $this->destiny_yard,
            'supplier' => $this->supplier,
            'client' => $this->client,
            'material' => $this->material,
            'receiptNumber' => $this->receipt_number,
            'referenceNumber' => $this->reference_number,
            'startDate' => $this->start_date,
            'finalDate' => $this->final_date,
            'licensePlate' => $this->license_plate,
            'trailerNumber' => $this->trailer_number,
            'driver' => $this->driver,
            'originGrossWeight' => $this->origin_gross_weight,
            'originTareWeight' => $this->origin_tare_weight,
            'originNetWeight' => $this->origin_net_weight,
            'destinyGrossWeight' => $this->destiny_gross_weight,
            'destinyTareWeight' => $this->destiny_tare_weight,
            'destinyNetWeight' => $this->destiny_net_weight,
            'transportationCompany' => $this->transportation_company,
            'originObservation' => $this->origin_observation,
            'destinyObservation' => $this->destiny_observation,
            'originSeal' => $this->origin_seal,
            'destinySeal' => $this->destiny_seal,
            'roundTrip' => $this->round_trip
        ];
    }
}
