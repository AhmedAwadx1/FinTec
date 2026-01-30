<?php

namespace App\Repositories\Account;

use App\Models\Account;

interface AccountRepositoryInterface
{
    public function create(array $data): Account;
    public function findById(int $id): ?Account;
    public function findByAccountNumber(string $accountNumber): ?Account;
    public function findByIdWithLock(int $id): ?Account;
    public function updateBalance(int $id, int $newBalance): bool;
    public function getLedgerEntries(int $accountId, int $perPage = 15);
    public function accountNumberExists(string $accountNumber): bool;
    public function generateUniqueAccountNumber(): string;
}
