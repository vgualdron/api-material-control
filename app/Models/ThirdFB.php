<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdFB extends Model
{
    public $table = "TERCERO";
    protected $connection = 'firebird';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ID',
        'NIT',
        'NOMBRE',        
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
            'id' => $this->ID,
            'nit' => $this->NIT,            
            'name' => $this->NOMBRE
        ];
    }
}
