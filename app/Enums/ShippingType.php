<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The ShippingType enum.
 *
 * @method static self PICKUP_AT_STORE()
 */
class ShippingType extends Enum
{
    const PICKUP_AT_STORE = 0;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::PICKUP_AT_STORE => trans('Pickup at Store'),
        ];
    }
}
