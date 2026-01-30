<?php

namespace App\Repositories\LedgerEntry;

use App\Models\LedgerEntry;

interface LedgerEntryRepositoryInterface
{
    public function create(array $data): LedgerEntry;
}
