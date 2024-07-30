<?php

namespace App\Models;

use App\Enums\OptionStatus;
use App\Enums\OptionType;
use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use SwitchTimezoneTrait;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quantity',
    ];

    /**
     * @return HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return MorphMany
     */
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'parent');
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
    protected function price(): Attribute
    {
        return Attribute::get(
            fn(float $price): array => pricePreview($price)
        );
    }

    /**
     * @return Attribute
     */
    protected function type(): Attribute
    {
        return Attribute::get(
            fn(int $type): string => OptionType::valueForKey($type)
        );
    }

    /**
     * @return Attribute
     */
    protected function status(): Attribute
    {
        return Attribute::get(
            fn(int $status): string => OptionStatus::valueForKey($status)
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
                    $item = imagePreview(
                        productImageUrl($item),
                        $this->version
                    );

                return array_values($items);
            }
        );
    }
}
