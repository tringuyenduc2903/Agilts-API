<?php

namespace App\Models;

use App\Trait\Models\SwitchTimezoneTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laravel\Scout\Searchable;

class Review extends Model
{
    use SwitchTimezoneTrait;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reviewable_id',
        'reviewable_type',
        'parent_id',
        'parent_type',
        'content',
        'rate',
        'images',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'reviewable_id',
        'reviewable_type',
        'parent_id',
        'parent_type',
        'reviewable',
        'parent',
    ];

    protected $appends = [
        'reviewable_preview',
    ];

    /**
     * @return MorphTo
     */
    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphOne
     */
    public function response(): MorphOne
    {
        return $this->morphOne(Review::class, 'parent')
            ->where('parent_type', Review::class)
            ->where('reviewable_type', User::class);
    }

    /**
     * @return Attribute
     */
    protected function images(): Attribute
    {
        return Attribute::make(
            get: function (string $images): array {
                $items = json_decode($images);

                foreach ($items as &$item)
                    $item = reviewImageUrl($item);

                return array_values($items);
            },
            set: fn(array $images): string => json_encode($images)
        );
    }

    /**
     * @return Attribute
     */
    protected function reviewablePreview(): Attribute
    {
        return Attribute::get(
            function (): array {
                /** @var Customer $reviewable */
                $reviewable = $this->reviewable;

                return [
                    'name' => $reviewable->name,
                ];
            }
        );
    }

    /**
     * @return Attribute
     */
    protected function parentPreview(): Attribute
    {
        return Attribute::get(
            function (): array {
                /** @var Option $parent */
                $parent = $this->parent;

                return [
                    'name' => $parent->product->name,
                    'sku' => $parent->sku,
                    'color' => $parent->color,
                ];
            }
        );
    }
}
