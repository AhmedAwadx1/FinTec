<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends AuthBaseModel
{
    const IMAGEPATH = 'users';


    protected $fillable = [
        'name',
        'email',
    ];

   
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
