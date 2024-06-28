<?php

namespace App\Models;

use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Identification extends Model
{
    use SwitchTimezoneTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'number',
        'issued_name',
        'issuance_date',
        'expiry_date',
        'default',
        'customer_id',
    ];

    protected $appends = [
        'type_preview',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'default' => 'boolean',
            'issuance_date' => 'date:' . config('app.timezone-format.short'),
            'expiry_date' => 'date:' . config('app.timezone-format.short'),
        ];
    }

    /**
     * @return Attribute
     */
    protected function typePreview(): Attribute
    {
        return Attribute::get(
            fn(): ?string => \App\Enums\Identification::valueForKey($this->type)
        );
    }
}
