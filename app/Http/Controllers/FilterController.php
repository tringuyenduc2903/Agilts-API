<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Option;
use App\Models\Product;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function product(): array
    {
        return [[
            'name' => 'type',
            'label' => trans('Type'),
            'data' => ProductType::values(),
        ], [
            'name' => 'minPrice',
            'label' => trans('Min price'),
            'data' => Option::min('price'),
        ], [
            'name' => 'maxPrice',
            'label' => trans('Max price'),
            'data' => Option::max('price'),
        ], [
            'name' => 'color',
            'label' => trans('Color'),
            'data' => Option::select('color')
                ->distinct()
                ->orderBy('color')
                ->pluck('color', 'color'),
        ], [
            'name' => 'version',
            'label' => trans('Version'),
            'data' => Option::select('version')
                ->distinct()
                ->orderBy('version')
                ->pluck('version', 'version'),
        ], [
            'name' => 'category',
            'label' => trans('Category'),
            'data' => Category::pluck('name', 'id'),
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
        $product = Product::findOrFail($product_id);

        return [[
            'name' => 'rate',
            'label' => trans('Rate'),
            'data' => [
                '1' => trans(
                    ':number Star (:count)', [
                    'number' => 1,
                    'count' => $product->reviews()->whereRate(1)->count(),
                ]),
                '2' => trans(
                    ':number Star (:count)', [
                    'number' => 2,
                    'count' => $product->reviews()->whereRate(2)->count(),
                ]),
                '3' => trans(
                    ':number Star (:count)', [
                    'number' => 3,
                    'count' => $product->reviews()->whereRate(3)->count(),
                ]),
                '4' => trans(
                    ':number Star (:count)', [
                    'number' => 4,
                    'count' => $product->reviews()->whereRate(4)->count(),
                ]),
                '5' => trans(
                    ':number Star (:count)', [
                    'number' => 5,
                    'count' => $product->reviews()->whereRate(5)->count(),
                ]),
                'negative' => trans(
                    ':type (:count)', [
                    'type' => trans('Negative'),
                    'count' => $product->reviews()->whereIn('rate', ['1', '2', '3'])->count(),
                ]),
                'positive' => trans(
                    ':type (:count)', [
                    'type' => trans('Positive'),
                    'count' => $product->reviews()->whereIn('rate', ['4', '5'])->count(),
                ]),
                'with_image' => trans(
                    ':type (:count)', [
                    'type' => trans('With image'),
                    'count' => $product->reviews()->whereJsonLength('reviews.images', '>', 0)->count(),
                ]),
            ],
        ]];
    }
}
