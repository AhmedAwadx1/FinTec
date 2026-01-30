<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AccountResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'account_number' => $this->account_number,
            'balance' => $this->balance,
            'currency' => $this->currency,
            'is_active' => $this->is_active,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
