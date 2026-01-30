<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function findByPhone(string $phone, string $countryCode): ?User;
}
