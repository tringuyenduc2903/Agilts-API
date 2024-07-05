<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Enums\ProductVisibility;
use App\Models\Product;
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
            ->whereIn('visibility', [
                ProductVisibility::CATALOG,
                ProductVisibility::CATALOG_AND_SEARCH,
            ])
            ->whereStatus(ProductStatus::IN_STOCK);

        if ($request->has('type'))
            $products->whereType($request->input('type'));

        if ($request->has('minPrice'))
            $products->whereHas(
                'options',
                fn(Builder $query): Builder => $query->where(
                    'price',
                    '>=',
                    $request->input('minPrice')
                )
            );

        if ($request->has('maxPrice'))
            $products->whereHas(
                'options',
                fn(Builder $query): Builder => $query->where(
                    'price',
                    '<=',
                    $request->input('maxPrice')
                )
            );

        $options = [
            'color',
            'model_name',
        ];

        foreach ($options as $option)
            if ($request->has($option))
                $products->whereHas(
                    'options',
                    fn(Builder $query): Builder => $query->where($option, $request->input($option))
                );

        if ($request->has('category'))
            $products->whereHas(
                'categories',
                fn(Builder $query): Builder => $query->where(
                    'categories.id',
                    $request->input('category')
                )
            );

        $paginator = $products->paginate($request->input('per_page'));

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
