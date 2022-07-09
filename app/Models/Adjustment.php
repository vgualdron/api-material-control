<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    public $table = "adjustment";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'type',
        'yard',
        'yard_name',
        'material',
        'material_name',
        'amount',
        'observation',
        'date',
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
            'yard' => $this->yard,
            'yardName' => $this->yard_name,
            'material' => $this->material,
            'materialName' => $this->material_name,
            'amount' => $this->amount,
            'observation' => $this->observation,
            'date' => $this->date,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
