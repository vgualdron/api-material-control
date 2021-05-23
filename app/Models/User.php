<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasRoles, HasApiTokens, Authenticatable, Authorizable, HasFactory;
    public $table = "user";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',        
        'document_number',
        'phone',
        'password',
        'yard',
        'role',
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
            'name' => $this->name,            
            'documentNumber' => $this->document_number,
            'phoneNumber' => $this->phone,
            'yard' => $this->yard,
            'role' => $this->role,
        ];
    }

    public function findForPassport($username){
        return $user = (new User)->where('document_number', $username)->first();
    }
}