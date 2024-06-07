<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The Gender enum.
 *
 * @method static self MALE()
 * @method static self FEMALE()
 * @method static self NOT_SPECIFIED()
 */
class Gender extends Enum
{
    const MALE = 0;
    const FEMALE = 1;
    const NOT_SPECIFIED = 2;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::MALE => trans('Male'),
            static::FEMALE => trans('Female'),
            static::NOT_SPECIFIED => trans('Not Specified'),
        ];
    }
}
