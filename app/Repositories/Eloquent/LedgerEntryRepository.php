<?php

namespace App\Repositories\Eloquent;

use App\Models\LedgerEntry;
use App\Repositories\Contracts\LedgerEntryRepositoryInterface;

class LedgerEntryRepository implements LedgerEntryRepositoryInterface
{
    public function create(array $data): LedgerEntry
    {
        return LedgerEntry::create($data);
    }
}
