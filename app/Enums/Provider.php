<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The Provider enum.
 *
 * @method static self GOOGLE()
 * @method static self FACEBOOK()
 */
class Provider extends Enum
{
    const GOOGLE = 0;
    const FACEBOOK = 1;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::GOOGLE => 'google',
            static::FACEBOOK => 'facebook',
        ];
    }
}
