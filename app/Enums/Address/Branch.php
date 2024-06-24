<?php

namespace App\Enums\Address;

use Rexlabs\Enum\Enum;

/**
 * The Branch enum.
 *
 * @method static self SHOP()
 * @method static self WAREHOUSE()
 */
class Branch extends Enum
{
    const SHOP = 0;
    const WAREHOUSE = 1;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::SHOP => trans('Shop'),
            static::WAREHOUSE => trans('Warehouse'),
        ];
    }
}
