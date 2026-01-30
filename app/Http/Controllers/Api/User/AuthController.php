<?php

namespace App\Http\Controllers\Api\User;

use App\Traits\ResponseTrait;
use App\Services\auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Http\Requests\Api\User\Auth\LoginRequest;
use App\Http\Requests\Api\User\Auth\RegisterRequest;

class AuthController extends Controller
{
    use ResponseTrait;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        
        $data = $this->authService->register($request->validated());
        return $this->response($data['key'], $data['msg'], $data['user'] == [] ? [] : new UserResource($data['user']));
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authService->login($request->validated());

        if ($data['key'] == 'fail') {
            return $this->failMsg($data['msg']);
        }

        $token = $data['user']->login();
        return $this->response('success', __('apis.signed'), UserResource::make($data['user'])->setToken($token));
    }
    public function logout()
    {
        auth('sanctum')->user()->tokens()->delete();
        return $this->successMsg(__('apis.signed_out'));
}
}