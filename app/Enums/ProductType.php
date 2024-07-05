<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The ProductType enum.
 *
 * @method static self NEW()
 * @method static self USED()
 * @method static self REFURBISHED()
 */
class ProductType extends Enum
{
    const NEW = 0;
    const USED = 1;
    const REFURBISHED = 2;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::NEW => trans('New product'),
            static::USED => trans('Used product'),
            static::REFURBISHED => trans('Refurbished product'),
        ];
    }
}
