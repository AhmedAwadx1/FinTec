<?php
namespace App\Http\Requests\Api\User\Auth;
use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest {

  public function rules() {
    return [
      'country_code' => 'required|numeric|digits_between:1,5',
      'phone'        => 'required|numeric|digits_between:9,10|exists:users,phone,deleted_at,NULL',
      'password'    => 'required|min:6|max:100',
    ];
  }

  public function prepareForValidation(){
    $this->merge([
      'phone' => fixPhone($this->phone),
      'country_code' => fixPhone($this->country_code),
    ]);
  }
}
