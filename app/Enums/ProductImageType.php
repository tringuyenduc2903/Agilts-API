<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The ProductImageType enum.
 *
 * @method static self BASE()
 * @method static self SMALL()
 * @method static self THUMBNAIL()
 */
class ProductImageType extends Enum
{
    const BASE = 0;
    const SMALL = 1;
    const THUMBNAIL = 2;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::BASE => trans('Base'),
            static::SMALL => trans('Small'),
            static::THUMBNAIL => trans('Thumbnail'),
        ];
    }
}
