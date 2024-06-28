<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The Gender enum.
 *
 * @method static self IDENTITY_CARD_9_NUMBERS()
 * @method static self IDENTITY_CARD_12_NUMBERS()
 * @method static self CITIZEN_IDENTIFICATION_CARD_BARCODE()
 * @method static self CITIZEN_IDENTIFICATION_CARD_CHIP()
 * @method static self IDENTIFICATION_CARD()
 * @method static self DIPLOMATIC_PASSPORT()
 * @method static self OFFICIAL_PASSPORT()
 * @method static self POPULAR_PASSPORT()
 */
class Identification extends Enum
{
    const IDENTITY_CARD_9_NUMBERS = 0;
    const IDENTITY_CARD_12_NUMBERS = 1;
    const CITIZEN_IDENTIFICATION_CARD_BARCODE = 2;
    const CITIZEN_IDENTIFICATION_CARD_CHIP = 3;
    const IDENTIFICATION_CARD = 4;
    const DIPLOMATIC_PASSPORT = 5;
    const OFFICIAL_PASSPORT = 6;
    const POPULAR_PASSPORT = 7;

    /**
     * Retrieve a map of enum keys and values.
     *
     * @return array
     */
    public static function map(): array
    {
        return [
            static::IDENTITY_CARD_9_NUMBERS => trans('Identity card (9 numbers)'),
            static::IDENTITY_CARD_12_NUMBERS => trans('Identity card (12 numbers)'),
            static::CITIZEN_IDENTIFICATION_CARD_BARCODE => trans('Citizen identification (barcode)'),
            static::CITIZEN_IDENTIFICATION_CARD_CHIP => trans('Citizen identification (chip)'),
            static::IDENTIFICATION_CARD => trans('Identity card'),
            static::DIPLOMATIC_PASSPORT => trans('Diplomatic passport'),
            static::OFFICIAL_PASSPORT => trans('Official passport'),
            static::POPULAR_PASSPORT => trans('Popular passport'),
        ];
    }
}
