<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Enums\ProductVisibility;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        $products = Product::query();

        $products
            ->whereEnabled(true)
            ->whereIn(
                'visibility', [
                ProductVisibility::CATALOG,
                ProductVisibility::CATALOG_AND_SEARCH,
            ])
            ->whereHas(
                'options',
                function (Builder $query) {
                    /** @var ProductOption $query */
                    return $query->whereStatus(ProductStatus::IN_STOCK);
                }
            );

        if ($request->exists('type'))
            $products->whereHas(
                'options',
                function (Builder $query) {
                    /** @var ProductOption $query */
                    return $query->whereStatus(request('type'));
                }
            );

        if ($request->exists('minPrice'))
            $products->whereHas(
                'options',
                fn(Builder $query): Builder => $query->where(
                    'price',
                    '>=',
                    request('minPrice')
                )
            );

        if ($request->exists('maxPrice'))
            $products->whereHas(
                'options',
                fn(Builder $query): Builder => $query->where(
                    'price',
                    '<=',
                    request('maxPrice')
                )
            );

        foreach (['color', 'version'] as $option)
            if ($request->exists($option))
                $products->whereHas(
                    'options',
                    fn(Builder $query): Builder => $query->where($option, request($option))
                );

        if ($request->exists('category'))
            $products->whereHas(
                'categories',
                fn(Builder $query): Builder => $query->where(
                    'categories.id',
                    request('category')
                )
            );

        $paginator = $products->paginate(request('per_page'));

        $paginator->append([
            'min_price',
            'max_price',
        ]);

        return $this->customPaginate($paginator);
    }

    /**
     * Display the specified resource.
     *
     * @param int $product_id
     * @return Product
     */
    public function show(int $product_id): Product
    {
        return Product::with([
            'categories',
            'options',
        ])->findOrFail($product_id);
    }
}
