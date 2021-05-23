<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    public $table = "material";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'code',
        'name',
        'unit',
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
            'unit' => $this->unit
        ];
    }
}
