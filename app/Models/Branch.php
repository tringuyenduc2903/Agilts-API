<?php

namespace App\Models;

use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Branch extends Model
{
    use SwitchTimezoneTrait;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'alt',
    ];

    protected $with = [
        'address',
    ];

    /**
     * @return MorphOne
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * @return Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::get(
            fn(?string $image): ?array => $image
                ? imagePreview(
                    branchImageUrl($image),
                    $this->alt
                )
                : null
        );
    }
}
