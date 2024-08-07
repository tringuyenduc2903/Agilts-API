<?php

namespace App\Models;

use App\Enums\ProductType;
use App\Enums\ProductVisibility;
use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use SoftDeletes;
    use SwitchTimezoneTrait;
    use Searchable;

    protected $with = [
        'options',
        'categories',
    ];

    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categories_products');
    }

    /**
     * @return HasManyThrough
     */
    public function reviews(): HasManyThrough
    {
        return $this->hasManyThrough(
            Review::class,
            Option::class,
            'product_id',
            'parent_id',
            'id',
            'id'
        )->where(
            'parent_type',
            Option::class
        );
    }

    /**
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    /**
     * @return MorphOne
     */
    public function seo(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->enabled && in_array(
                $this->getRawOriginal('visibility'), [
                ProductVisibility::SEARCH,
                ProductVisibility::CATALOG_AND_SEARCH,
            ]);
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
                    $item->image = productImageUrl($item->image);

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
                        $item->image = productImageUrl($item->image);

                    unset($item->video->title, $item->video->image);
                }

                return $items;
            }
        );
    }

    /**
     * @return Attribute
     */
    protected function optionsMinPrice(): Attribute
    {
        return Attribute::get(
            fn(float $price): array => pricePreview($price)
        );
    }

    /**
     * @return Attribute
     */
    protected function optionsMaxPrice(): Attribute
    {
        return Attribute::get(
            fn(float $price): array => pricePreview($price)
        );
    }
}
