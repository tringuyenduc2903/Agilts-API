<?php

namespace App\Models;

use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductList extends Model
{
    use SwitchTimezoneTrait;

    protected $table = 'lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'option_id',
        'customer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'type',
        'option_id',
        'customer_id',
    ];

    /**
     * @return BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    /**
     * @return Attribute
     */
    public function productPreview(): Attribute
    {
        return Attribute::get(
            function (): array {
                /** @var Option $option */
                $option = $this->option;

                $product = $option->product;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'option_id' => $option->id,
                    'sku' => $option->sku,
                    'color' => $option->color,
                    'categories' => $product->categories->pluck('name')->toArray(),
                ];
            }
        );
    }
}
