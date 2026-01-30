<?php

namespace App\Repositories\Transfer;

use App\Models\Transfer;

interface TransferRepositoryInterface
{
    public function create(array $data): Transfer;
    public function findById(int $id): ?Transfer;
    public function updateStatus(int $id, int $status, ?string $failureReason = null): bool;
}
