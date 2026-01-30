<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;

class CreateAccountRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id,deleted_at,NULL',
            'currency' => 'nullable|string|size:3',
            'balance' => 'required|integer|min:0',
        ];
    }
   
}
