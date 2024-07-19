<?php

namespace App\Models;

use App\Enums\CustomerAddress;
use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use SwitchTimezoneTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'country',
        'province',
        'district',
        'ward',
        'address_detail',
        'type',
        'default',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'addressable_type',
        'addressable_id',
    ];

    protected $appends = [
        'address_preview',
    ];

    /**
     * @return array
     */
    public function getHidden(): array
    {
        if ($this->addressable_type !== Branch::class)
            return parent::getHidden();

        return array_merge(
            parent::getHidden(), [
            'type',
            'default'
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
            'default' => 'boolean',
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
            fn(): string => CustomerAddress::valueForKey($this->type)
        );
    }
}
