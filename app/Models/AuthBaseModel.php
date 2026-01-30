<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class AuthBaseModel extends Authenticatable
{
    use UploadTrait, HasApiTokens, SoftDeletes;

    const IMAGEPATH = 'users';

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function setImageAttribute($value)
    {
        if ($value && is_file($value)) {
            isset($this->attributes['image']) ? $this->deleteFile($this->attributes['image'], self::IMAGEPATH) : '';
            $this->attributes['image'] = $this->uploadAllTyps($value, self::IMAGEPATH);
        }
    }

    public function getImageAttribute()
    {
        if ($this->attributes['image'] ?? null) {
            return $this->getImage($this->attributes['image'], self::IMAGEPATH);
        }
        return $this->defaultImage(self::IMAGEPATH);
    }
    public function getFullNameAttribute()
    {
        return $this->country_code . ' ' . $this->phone;
    }

    public function login()
    {
        return $this->createToken('auth_token')->plainTextToken;
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            if (isset($model->attributes['image'])) {
                $model->deleteFile($model->attributes['image'], self::IMAGEPATH);
            }
        });
    }
}
