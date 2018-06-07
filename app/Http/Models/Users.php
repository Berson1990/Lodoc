<?php

namespace App\Http\Models;

use \Illuminate\Database\Eloquent\Model;

class Users extends Model
{

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = ['name', 'token_id', 'phone', 'mail', 'address', 'password', 'c-password', 'type', 'state', 'profile'];
    protected $hidden = ['password', 'c-password'];

    public function Doctors()
    {
        return $this->hasMany('App\Http\Models\Doctors', 'user_id');
    }
}