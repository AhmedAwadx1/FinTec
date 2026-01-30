<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function all(): Collection
    {
        return User::select('id', 'name', 'email')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return User::select('id', 'name', 'email')->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
