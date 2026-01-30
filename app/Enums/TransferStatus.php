<?php

namespace App\Enums;

/**
 * Class TransferStatus
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class TransferStatus extends Base
{
    public const PENDING = 0;
    public const SUCCESS = 1;
    public const FAILED = 2;
}
