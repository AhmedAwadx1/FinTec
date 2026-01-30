<?php

namespace App\Repositories\LedgerEntry;

use App\Models\LedgerEntry;

class LedgerEntryRepository implements LedgerEntryRepositoryInterface
{
    public function create(array $data): LedgerEntry
    {
        return LedgerEntry::create($data);
    }
}
