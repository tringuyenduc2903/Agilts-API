<?php

namespace App\Models;

use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceProduct extends Model
{
    use SwitchTimezoneTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'price',
        'amount',
        'value_added_tax',
        'invoice_id',
        'option_id',
        'vehicle_id',
    ];

    protected $appends = [
        'price_preview',
        'value_added_tax_preview',
    ];

    /**
     * @return BelongsTo
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class)->withTrashed();
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
    protected function valueAddedTaxPreview(): Attribute
    {
        return Attribute::get(
            fn(): string => formatPrice($this->value_added_tax)
        );
    }
}
