<?php

namespace App\Models;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOption extends Model
{
    use SwitchTimezoneTrait;

    protected $appends = [
        'price_preview',
    ];

    /**
     * @return HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(ProductDetail::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'specifications' => 'array',
        ];
    }

    /**
     * @return Attribute
     */
    protected function pricePreview(): Attribute
    {
        return Attribute::get(
            fn(): string => formatPrice($this->price)
        );
    }

    /**
     * @return Attribute
     */
    protected function type(): Attribute
    {
        return Attribute::get(
            fn(int $type): string => ProductType::valueForKey($type)
        );
    }

    /**
     * @return Attribute
     */
    protected function status(): Attribute
    {
        return Attribute::get(
            fn(int $status): string => ProductStatus::valueForKey($status)
        );
    }

    /**
     * @return Attribute
     */
    protected function images(): Attribute
    {
        return Attribute::get(
            function (string $images): array {
                $items = json_decode($images);

                foreach ($items as &$item)
                    $item = productImageUrl($item);

                return array_values($items);
            }
        );
    }
}
