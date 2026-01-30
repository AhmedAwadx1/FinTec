<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends BaseModel
{
    const IMAGEPATH = 'accounts';

    protected $fillable = [
        'user_id',
        'account_number',
        'balance',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'integer',
        'is_active' => 'boolean',
    ];

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outgoingTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'source_account_id');
    }

    public function incomingTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'destination_account_id');
    }

   
    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

   

   
    public static function generateAccountNumber(): string
    {
        do {
            $accountNumber = 'ACC' . str_pad((string) mt_rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
        } while (self::where('account_number', $accountNumber)->exists());

        return $accountNumber;
    }
}
