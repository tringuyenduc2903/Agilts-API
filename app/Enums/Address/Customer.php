<?php

namespace App\Enums\Address;

use Rexlabs\Enum\Enum;

/**
 * The Customer enum.
 *
 * @method static self HOME()
 * @method static self COMPANY()
 */
class Customer extends Enum
{
    const HOME = 0;
    const COMPANY = 1;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::HOME => trans('Home'),
            static::COMPANY => trans('Company'),
        ];
    }
}
