<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByPhone(string $phone, string $countryCode): ?User
    {
        return User::where([
            'phone' => $phone,
            'country_code' => $countryCode
        ])->first();
    }
}
