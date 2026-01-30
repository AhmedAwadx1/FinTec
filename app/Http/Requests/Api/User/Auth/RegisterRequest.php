<?php

namespace App\Http\Requests\Api\User\Auth;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest {
  
  public function rules() {
   
    return [
      'name'         => 'required|max:50',
      'country_code' => 'required|numeric|digits_between:2,5',
      'phone'        => 'required|numeric|digits_between:9,10|unique:users,phone,NULL,id,deleted_at,NULL',
      'email'        => 'required|email|unique:users,email,NULL,id,deleted_at,NULL|max:50',
      'password'     => 'required|min:6|max:100',
      'lang'         => 'required|in:ar,en',
      'image'        => 'nullable|image',
    ];
  }

  public function prepareForValidation() {
    $this->merge([
      'phone' => fixPhone($this->phone) ,
      'country_code' => fixPhone($this->country_code) , 
    ]);
  }

}
