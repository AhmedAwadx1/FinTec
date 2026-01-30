<?php

namespace App\Models;

use App\Enums\TransferStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transfer extends BaseModel
{
    protected $fillable = [
        'source_account_id',
        'destination_account_id',
        'amount',
        'status',
        'failure_reason',
        'reference',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'status' => 'integer',
    ];

  
    public function sourceAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'source_account_id');
    }


    public function destinationAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'destination_account_id');
    }

   
    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

    
    public function isSuccessful(): bool
    {
        return $this->status === TransferStatus::SUCCESS;
    }

   
    public function isPending(): bool
    {
        return $this->status === TransferStatus::PENDING;
    }

  
    public function isFailed(): bool
    {
        return $this->status === TransferStatus::FAILED;
    }

  
    public static function generateReference(): string
    {
        return 'TXN' . date('Ymd') . strtoupper(uniqid());
    }
}
