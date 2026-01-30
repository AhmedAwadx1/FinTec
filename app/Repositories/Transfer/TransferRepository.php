<?php

namespace App\Repositories\Transfer;

use App\Models\Transfer;

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

    public function updateStatus(int $id, int $status, ?string $failureReason = null): bool
    {
        $data = ['status' => $status];
        if ($failureReason !== null) {
            $data['failure_reason'] = $failureReason;
        }
        return Transfer::where('id', $id)->update($data);
    }
}
