<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdFB extends Model
{
    public $table = "TERCEROS";
    protected $connection = 'firebird';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'TERID',
        'NIT',
        'NOMBRE',
        'CLIENTE',
        'ASOCIADO',
        'CONTRATISTA',
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
            'id' => $this->TERID,
            'nit' => $this->NIT,
            'name' => $this->NOMBRE,
            'customer' => $this->CLIENTE,
            'associated' => $this->ASOCIADO,
            'contractor' => $this->CONTRATISTA
        ];
    }
}