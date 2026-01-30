<?php

namespace App\Enums;

/**
 * Class LedgerEntryType
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class LedgerEntryType extends Base
{
    public const DEBIT = 0;
    public const CREDIT = 1;
}
