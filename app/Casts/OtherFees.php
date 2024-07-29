<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class OtherFees implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array<string, mixed> $attributes
     * @return object
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): object
    {
        $other_fees = json_decode($value);

        foreach ([
                     'vehicle_registration_support_fee',
                     'registration_fee',
                     'license_plate_registration_fee',
                 ] as $key)
            if (isset($other_fees->$key))
                $other_fees->$key = [
                    'raw' => $other_fees->$key,
                    'preview' => formatPrice($other_fees->$key),
                ];

        return $other_fees;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array<string, mixed> $attributes
     * @return string
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return json_encode($value);
    }
}
