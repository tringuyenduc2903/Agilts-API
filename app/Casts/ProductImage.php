<?php

namespace App\Casts;

use App\Enums\ProductImageType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ProductImage implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $items = json_decode($value);

        foreach ($items as $item) {
            $item->hided = (bool)$item->hided;

            $types = $item->type;
            $item->type = [];

            if (is_null($types))
                continue;

            foreach ($types as $type) {
                $item->type[] = ProductImageType::valueForKey($type);
            }
        }

        return $items;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return json_encode($value);
    }
}
