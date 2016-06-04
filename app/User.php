<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'is_admin', 'my_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Юзер дминистратор?
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin ? true : false;
    }

    public function myAdmin()
    {
        return $this->belongsTo(User::class, 'my_admin', 'id');
    }
}
