<?php

namespace App\Models;

use App\Enums\LedgerEntryType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LedgerEntry extends BaseModel
{
    protected $fillable = [
        'account_id',
        'transfer_id',
        'amount',
        'type',
        'balance_after',
        'description',
    ];

    protected $casts = [
        'amount' => 'integer',
        'type' => 'integer',
        'balance_after' => 'integer',
    ];

    
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

   
    public function transfer(): BelongsTo
    {
        return $this->belongsTo(Transfer::class);
    }

  
    public function isDebit(): bool
    {
        return $this->type === LedgerEntryType::DEBIT;
    }

   
    public function isCredit(): bool
    {
        return $this->type === LedgerEntryType::CREDIT;
    }

}
