<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Third extends Model
{
    public $table = "rate";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'code',
        'nit',
        'name',        
        'type',        
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
            'nit' => $this->nit,
            'name' => $this->name,
            'type' => $this->type            
        ];
    }
}
