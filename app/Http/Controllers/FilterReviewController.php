<?php

namespace App\Http\Controllers;

use App\Models\Product;

class FilterReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $product_id
     * @return array
     */
    public function __invoke(int $product_id): array
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
                    'count' => $product->reviews()->whereJsonLength('product_reviews.images', '>', 0)->count(),
                ]),
            ],
        ]];
    }
}
