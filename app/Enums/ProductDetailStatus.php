<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The ProductDetailStatus enum.
 *
 * @method static self STORAGE()
 * @method static self SOLD()
 */
class ProductDetailStatus extends Enum
{
    const STORAGE = 0;
    const SOLD = 1;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::STORAGE => trans('Storage'),
            static::SOLD => trans('Sold'),
        ];
    }
}
