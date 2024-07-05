<?php

namespace App\Models;

use App\Casts\ProductImage;
use App\Casts\ProductVideo;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\ProductVisibility;
use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    use SwitchTimezoneTrait;

    protected $appends = [
        'min_price',
        'max_price',
    ];

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categories_products');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'images' => ProductImage::class,
            'videos' => ProductVideo::class,
            'enabled' => 'boolean',
            'specifications' => 'array',
        ];
    }

    /**
     * @return Attribute
     */
    protected function visibility(): Attribute
    {
        return Attribute::get(
            fn(int $visibility): string => ProductVisibility::valueForKey($visibility)
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
    protected function type(): Attribute
    {
        return Attribute::get(
            fn(int $type): string => ProductType::valueForKey($type)
        );
    }

    /**
     * @return Attribute
     */
    protected function minPrice(): Attribute
    {
        return Attribute::get(
            function (): array {
                $price = $this->options()->min('price');

                return [
                    'raw' => $price,
                    'preview' => formatPrice($price)
                ];
            }
        );
    }

    /**
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    /**
     * @return Attribute
     */
    protected function maxPrice(): Attribute
    {
        return Attribute::get(
            function (): array {
                $price = $this->options()->max('price');

                return [
                    'raw' => $price,
                    'preview' => formatPrice($price)
                ];
            }
        );
    }
}
