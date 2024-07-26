<?php

namespace App\Models;

use App\Casts\OtherFields;
use App\Enums\OrderStatus;
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
        'vehicle_registration_support_fee',
        'registration_fee',
        'license_plate_registration_fee',
        'total',
        'status',
        'note',
        'other_fields',
        'address_id',
        'identification_id',
        'customer_id',
    ];

    protected $appends = [
        'total_preview',
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
    protected function taxPreview(): Attribute
    {
        return Attribute::get(
            fn(): string => formatPrice($this->tax)
        );
    }

    /**
     * @return Attribute
     */
    protected function shippingFeePreview(): Attribute
    {
        return Attribute::get(
            fn(): string => formatPrice($this->shipping_fee)
        );
    }

    /**
     * @return Attribute
     */
    protected function vehicleRegistrationSupportFeePreview(): Attribute
    {
        return Attribute::get(
            fn(): string => formatPrice($this->vehicle_registration_support_fee)
        );
    }

    /**
     * @return Attribute
     */
    protected function registrationFeePreview(): Attribute
    {
        return Attribute::get(
            fn(): string => formatPrice($this->registration_fee)
        );
    }

    /**
     * @return Attribute
     */
    protected function licensePlateRegistrationFeePreview(): Attribute
    {
        return Attribute::get(
            fn(): string => formatPrice($this->license_plate_registration_fee)
        );
    }

    /**
     * @return Attribute
     */
    protected function totalPreview(): Attribute
    {
        return Attribute::get(
            fn(): string => formatPrice($this->total)
        );
    }
}
