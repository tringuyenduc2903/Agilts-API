<?php

namespace App\Casts;

use App\Enums\LicensePlateRegistrationOption;
use App\Enums\RegistrationOption;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class OtherFields implements CastsAttributes
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
        $other_fields = json_decode($value);

        if (isset($other_fields->vehicle_registration_support))
            $other_fields->vehicle_registration_support = (bool)$other_fields->vehicle_registration_support;

        if (isset($other_fields->registration_option))
            $other_fields->registration_option = RegistrationOption::valueForKey($other_fields->registration_option);

        if (isset($other_fields->license_plate_registration_option))
            $other_fields->license_plate_registration_option = LicensePlateRegistrationOption::valueForKey($other_fields->license_plate_registration_option);

        return $other_fields;
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
