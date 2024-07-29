<?php

namespace App\Models;

use App\Casts\OtherFees;
use App\Casts\OtherFields;
use App\Enums\OrderStatus;
use App\Enums\ShippingType;
use App\Enums\TransactionType;
use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use SwitchTimezoneTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tax',
        'shipping_fee',
        'handling_fee',
        'other_fees',
        'total',
        'status',
        'note',
        'shipping_type',
        'transaction_type',
        'other_fields',
        'address_id',
        'identification_id',
        'customer_id',
    ];

    /**
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function identification(): BelongsTo
    {
        return $this->belongsTo(Identification::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    /**
     * @return HasMany
     */
    public function invoice_products(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'other_fees' => OtherFees::class,
            'other_fields' => OtherFields::class,
        ];
    }

    /**
     * @return Attribute
     */
    protected function status(): Attribute
    {
        return Attribute::get(
            fn(int $status): string => OrderStatus::valueForKey($status)
        );
    }

    /**
     * @return Attribute
     */
    protected function tax(): Attribute
    {
        return Attribute::get(
            fn(float $price): array => pricePreview($price)
        );
    }

    /**
     * @return Attribute
     */
    protected function shippingFee(): Attribute
    {
        return Attribute::get(
            fn(float $price): array => pricePreview($price)
        );
    }

    /**
     * @return Attribute
     */
    protected function handlingFee(): Attribute
    {
        return Attribute::get(
            fn(float $price): array => pricePreview($price)
        );
    }

    /**
     * @return Attribute
     */
    protected function total(): Attribute
    {
        return Attribute::get(
            fn(float $price): array => pricePreview($price)
        );
    }

    /**
     * @return Attribute
     */
    protected function shippingType(): Attribute
    {
        return Attribute::get(
            fn(int $shipping_type): string => ShippingType::valueForKey($shipping_type)
        );
    }

    /**
     * @return Attribute
     */
    protected function transactionType(): Attribute
    {
        return Attribute::get(
            fn(int $transaction_type): string => TransactionType::valueForKey($transaction_type)
        );
    }
}
