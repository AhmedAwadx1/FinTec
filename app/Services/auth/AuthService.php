<?php

namespace App\Services\auth;

use App\Repositories\User\UserRepositoryInterface;
use App\Http\Resources\Api\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        try {
            $user = $this->userRepository->create($data);
            return [
                'key' => 'success',
                'msg' => __('auth.registered'),
                'user' => new UserResource($user)
            ];
        } catch (\Exception $e) {
            dd($e);
            return [
                'key' => 'fail',
                'msg' => __('site.wrong'),
                'user' => []
            ];
        }
    }

    public function login(array $data)
    {
        $user = $this->userRepository->findByPhone($data['phone'], $data['country_code']);

        if (!$user) {
            return [
                'key' => 'fail',
                'msg' => __('auth.incorrect_key_or_phone'),
                'user' => []
            ];
        }

        $storedPassword = $user->getAuthPassword();
        
        // Check if password is hashed (bcrypt hashes start with $2y$, $2a$, or $2b$)
        $isHashed = preg_match('/^\$2[ayb]\$.{56}$/', $storedPassword);
        
        if ($isHashed) {
            // Password is hashed, use Hash::check
            if (!Hash::check($data['password'], $storedPassword)) {
                return [
                    'key' => 'fail',
                    'msg' => __('auth.incorrect_pass'),
                    'user' => []
                ];
            }
        } else {
            // Password is plain text (legacy), compare directly and rehash
            if ($data['password'] !== $storedPassword) {
                return [
                    'key' => 'fail',
                    'msg' => __('auth.incorrect_pass'),
                    'user' => []
                ];
            }
            // Rehash the password for security
            $user->password = $data['password'];
            $user->save();
        }

        return [
            'key' => 'success',
            'msg' => __('auth.signed'),
            'user' => $user
        ];
    }
}
