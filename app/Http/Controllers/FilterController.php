<?php

namespace App\Http\Controllers;

use App\Enums\OptionStatus;
use App\Enums\OptionType;
use App\Enums\ProductType;
use App\Enums\ProductVisibility;
use App\Models\Category;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function product(): array
    {
        $product = Product::whereEnabled(true)
            ->whereNot('visibility', ProductVisibility::NOT_VISIBLE_INDIVIDUALLY)
            ->whereHas(
                'options',
                function (Builder $query) {
                    /** @var Option $query */
                    return $query->whereStatus(OptionStatus::IN_STOCK);
                }
            );

        $price = $product->clone()->withMin('options', 'price');

        $option = Option::whereStatus(OptionStatus::IN_STOCK)
            ->whereHas('product', function (Builder $query) {
                /** @var Product $query */
                return $query->whereEnabled(true)
                    ->whereNot('visibility', ProductVisibility::NOT_VISIBLE_INDIVIDUALLY);
            });

        $category = Category::whereHas('product', function (Builder $query) {
            /** @var Product $query */
            return $query->whereEnabled(true)
                ->whereNot('visibility', ProductVisibility::NOT_VISIBLE_INDIVIDUALLY);
        })->whereHas('product.options', function (Builder $query) {
            /** @var Option $query */
            return $query->whereStatus(OptionStatus::IN_STOCK);
        });

        return [[
            'name' => 'product_type',
            'label' => trans('Type'),
            'data' => ProductType::values(),
        ], [
            'name' => 'option_type',
            'label' => trans('Type'),
            'data' => OptionType::values(),
        ], [
            'name' => 'manufacturer',
            'label' => trans('Manufacturer'),
            'data' => $product->clone()
                ->select('manufacturer')
                ->distinct()
                ->orderBy('manufacturer')
                ->pluck('manufacturer', 'manufacturer'),
        ], [
            'name' => 'minPrice',
            'label' => trans('Min price'),
            'data' => $price->clone()
                ->orderBy('options_min_price')
                ->first()
                ->options_min_price,
        ], [
            'name' => 'maxPrice',
            'label' => trans('Max price'),
            'data' => $price->clone()
                ->orderByDesc('options_min_price')
                ->first()
                ->options_min_price,
        ], [
            'name' => 'color',
            'label' => trans('Color'),
            'data' => $option->clone()
                ->select('color')
                ->distinct()
                ->orderBy('color')
                ->pluck('color', 'color'),
        ], [
            'name' => 'version',
            'label' => trans('Version'),
            'data' => $option->clone()
                ->select('version')
                ->distinct()
                ->orderBy('version')
                ->pluck('version', 'version'),
        ], [
            'name' => 'category',
            'label' => trans('Category'),
            'data' => $category->clone()
                ->pluck('name', 'id'),
        ]];
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $product_id
     * @return array
     */
    public function review(int $product_id): array
    {
        $reviews = Product::findOrFail($product_id)->reviews();

        return [[
            'name' => 'rate',
            'label' => trans('Rate'),
            'data' => [
                '1' => trans(
                    ':number Star (:count)', [
                    'number' => 1,
                    'count' => $reviews->clone()
                        ->whereRate(1)->count(),
                ]),
                '2' => trans(
                    ':number Star (:count)', [
                    'number' => 2,
                    'count' => $reviews->clone()
                        ->whereRate(2)->count(),
                ]),
                '3' => trans(
                    ':number Star (:count)', [
                    'number' => 3,
                    'count' => $reviews->clone()
                        ->whereRate(3)->count(),
                ]),
                '4' => trans(
                    ':number Star (:count)', [
                    'number' => 4,
                    'count' => $reviews->clone()
                        ->whereRate(4)->count(),
                ]),
                '5' => trans(
                    ':number Star (:count)', [
                    'number' => 5,
                    'count' => $reviews->clone()
                        ->whereRate(5)->count(),
                ]),
                'negative' => trans(
                    ':type (:count)', [
                    'type' => trans('Negative'),
                    'count' => $reviews->clone()
                        ->whereIn('rate', ['1', '2', '3'])->count(),
                ]),
                'positive' => trans(
                    ':type (:count)', [
                    'type' => trans('Positive'),
                    'count' => $reviews->clone()
                        ->whereIn('rate', ['4', '5'])->count(),
                ]),
                'with_image' => trans(
                    ':type (:count)', [
                    'type' => trans('With image'),
                    'count' => $reviews->clone()
                        ->whereJsonLength('reviews.images', '>', 0)->count(),
                ]),
            ],
        ]];
    }
}
