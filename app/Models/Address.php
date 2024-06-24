<?php

namespace App\Models;

use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    use SwitchTimezoneTrait;

    protected $hidden = [
        'addressable_type',
        'addressable_id',
    ];

    protected $appends = [
        'address_preview',
        'type_preview',
    ];

    /**
     * @return MorphTo
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'default' => 'bool',
        ];
    }

    /**
     * @return Attribute
     */
    protected function addressPreview(): Attribute
    {
        return Attribute::get(
            fn(): string => "$this->address_detail, $this->ward, $this->district, $this->province"
        );
    }

    /**
     * @return Attribute
     */
    protected function typePreview(): Attribute
    {
        return Attribute::get(
            fn(): string => \App\Enums\Address\Branch::valueForKey($this->type)
        );
    }
}
