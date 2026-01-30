<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Models\LedgerEntry;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AccountRepository implements AccountRepositoryInterface
{
    public function create(array $data): Account
    {
        return Account::create($data);
    }

    public function all(): Collection
    {
        return Account::all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Account::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?Account
    {
        return Account::find($id);
    }
    

    public function findByAccountNumber(string $accountNumber): ?Account
    {
        return Account::where('account_number', $accountNumber)->first();
    }

    public function findByIdWithLock(int $id): ?Account
    {
        return Account::where('id', $id)->lockForUpdate()->first();
    }

    public function updateBalance(int $id, int $newBalance): bool
    {
        return Account::where('id', $id)->update(['balance' => $newBalance]);
    }

    public function getLedgerEntries(int $accountId, int $perPage = 15): LengthAwarePaginator
    {
        return LedgerEntry::where('account_id', $accountId)
            ->with(['transfer'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function accountNumberExists(string $accountNumber): bool
    {
        return Account::where('account_number', $accountNumber)
            ->lockForUpdate()
            ->exists();
    }

    public function generateUniqueAccountNumber(): string
    {
        do {
            $accountNumber = 'ACC' . str_pad((string) mt_rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
        } while ($this->accountNumberExists($accountNumber));

        return $accountNumber;
    }
}
