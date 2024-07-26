<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The RegistrationOption enum.
 *
 * @method static self FIRST_TIME()
 * @method static self TWO_TIME_ONWARDS()
 */
class RegistrationOption extends Enum
{
    const FIRST_TIME = 0;
    const TWO_TIME_ONWARDS = 1;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::FIRST_TIME => trans('First time (5%)'),
            static::TWO_TIME_ONWARDS => trans('Two time onwards (1%)'),
        ];
    }
}
