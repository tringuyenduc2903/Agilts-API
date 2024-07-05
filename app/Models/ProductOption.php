<?php

namespace App\Models;

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
            'images' => 'array',
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
}
