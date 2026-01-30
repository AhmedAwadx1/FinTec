<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest {
  use ResponseTrait;

  protected function failedValidation(Validator $validator) {
    if (request()->is('api/*')) {
      throw new HttpResponseException($this->response('fail', $validator->errors()->first()));
    }else{
      throw (new ValidationException($validator))
        ->errorBag($this->errorBag)
        ->redirectTo($this->getRedirectUrl());
    }
  }
}
