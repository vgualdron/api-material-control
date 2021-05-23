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
        'type',
        'origin_yard',
        'destiny_yard',
        'supplier',
        'client',
        'start_date',
        'final_date',
        'material',
        'freight_price',
        'material_price',
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
            'type' => $this->type,            
            'originYard' => $this->origin_yard,
            'destinyYard' => $this->destiny_yard,
            'supplier' => $this->supplier,
            'client' => $this->client,
            'startDate' => $this->start_date,
            'finalDate' => $this->final_date,
            'freightPrice' => $this->freight_price,
            'materialPrice' => $this->material_price,
            'observation' => $this->observation,
            'roundTrip' => $this->round_trip
        ];
    }
}
