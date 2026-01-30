<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TransferResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'transfer_id' => $this->id,
            'reference' => $this->reference,
            'source_account_id' => $this->source_account_id,
            'destination_account_id' => $this->destination_account_id,
            'amount' => $this->amount,
            'amount_in_dollars' => $this->amount_in_dollars,
            'status' => $this->status,
            'status_name' => $this->isSuccessful() ? 'SUCCESS' : ($this->isPending() ? 'PENDING' : 'FAILED'),
            'failure_reason' => $this->failure_reason,
            'processed_at' => $this->processed_at,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            
        ];
    }
}
