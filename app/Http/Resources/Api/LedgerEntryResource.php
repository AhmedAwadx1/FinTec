<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class LedgerEntryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transfer_id' => $this->transfer_id,
            'transfer_reference' => $this->when($this->transfer, $this->transfer->reference),
            'amount' => $this->amount,
            'type' => $this->type,
            'type_name' => $this->isDebit() ? 'DEBIT' : 'CREDIT',
            'balance_after' => $this->balance_after,
            'description' => $this->description,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
