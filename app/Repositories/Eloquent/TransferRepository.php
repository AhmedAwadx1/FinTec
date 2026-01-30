<?php

namespace App\Repositories\Eloquent;

use App\Models\Transfer;
use App\Repositories\Contracts\TransferRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TransferRepository implements TransferRepositoryInterface
{
    public function create(array $data): Transfer
    {
        return Transfer::create($data);
    }

    public function findById(int $id): ?Transfer
    {
        return Transfer::find($id);
    }

    public function updateStatus(int $id, int $status, ?string $failureReason = null, ?Carbon $processedAt = null): bool
    {
        $data = ['status' => $status];
        
        if ($failureReason !== null) {
            $data['failure_reason'] = $failureReason;
        }
        
        if ($processedAt !== null) {
            $data['processed_at'] = $processedAt;
        } elseif ($processedAt === null && ($status === 1 || $status === 2)) {
            // Auto-set processed_at for success/failed statuses if not provided
            $data['processed_at'] = Carbon::now();
        }
        
        return Transfer::where('id', $id)->update($data);
    }

    public function all(): Collection
    {
        return Transfer::all();
    }
}
