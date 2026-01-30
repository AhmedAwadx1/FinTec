<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;

class CreateUserRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL|max:50',
        ];
    }
}
