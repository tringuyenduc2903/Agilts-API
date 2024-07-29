<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The TransactionType enum.
 *
 * @method static self PAYMENT_ON_DELIVERY()
 */
class TransactionType extends Enum
{
    const PAYMENT_ON_DELIVERY = 0;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::PAYMENT_ON_DELIVERY => trans('Payment on delivery'),
        ];
    }
}
