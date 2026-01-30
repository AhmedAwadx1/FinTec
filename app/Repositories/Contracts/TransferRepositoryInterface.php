<?php

namespace App\Repositories\Contracts;

use App\Models\Transfer;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

interface TransferRepositoryInterface
{
    public function create(array $data): Transfer;
    public function findById(int $id): ?Transfer;
    public function updateStatus(int $id, int $status, ?string $failureReason = null, ?Carbon $processedAt = null): bool;
    public function all(): Collection;
}
