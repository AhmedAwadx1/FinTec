<?php

namespace App\Repositories\Contracts;

use App\Models\Account;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AccountRepositoryInterface
{
    public function create(array $data): Account;
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?Account;
    public function findByAccountNumber(string $accountNumber): ?Account;
    public function findByIdWithLock(int $id): ?Account;
    public function updateBalance(int $id, int $newBalance): bool;
    public function getLedgerEntries(int $accountId, int $perPage = 15): LengthAwarePaginator;
    public function accountNumberExists(string $accountNumber): bool;
    public function generateUniqueAccountNumber(): string;
}
