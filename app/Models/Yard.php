<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Yard extends Model
{
    public $table = "yard";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'code',
        'name',
        'zone',
        'zoneName',
        'latitude',
        'longitude',
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
            'code' => $this->code,
            'name' => $this->name,            
            'zone' => $this->zone,
            'zoneName' => $this->zoneName,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }
}
