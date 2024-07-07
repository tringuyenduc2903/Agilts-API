<?php

namespace App\Models;

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
    protected function images(): Attribute
    {
        return Attribute::get(
            function (string $images): array {
                $items = json_decode($images);

                foreach ($items as $index => $item) {
                    $item->image = secure_url(config('filesystems.disks.product.url') . $item->image);

                    if ($item->hided)
                        unset($items[$index]);

                    unset($item->hided);
                }

                return array_values($items);
            }
        );
    }

    /**
     * @return Attribute
     */
    protected function videos(): Attribute
    {
        return Attribute::get(
            function (string $videos): array {
                $items = json_decode($videos);

                foreach ($items as $item) {
                    $item->video = json_decode($item->video);

                    if (is_null($item->title))
                        $item->title = $item->video->title;

                    if (is_null($item->image))
                        $item->image = $item->video->image;
                    else
                        $item->image = secure_url(config('filesystems.disks.product.url') . $item->image);

                    unset($item->video->title, $item->video->image);
                }

                return $items;

            }
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
