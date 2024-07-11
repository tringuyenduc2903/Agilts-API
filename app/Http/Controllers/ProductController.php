<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Enums\ProductVisibility;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOption;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        $products = $request->exists('search')
            ? $this->search()
            : $this->catalog();

        $paginator = $products->paginate(request('per_page'));

        /** @var Collection $paginator */
        $paginator->append([
            'min_price',
            'max_price',
        ]);

        return $this->customPaginate($paginator);
    }

    /**
     * @return \Laravel\Scout\Builder
     */
    protected function search(): \Laravel\Scout\Builder
    {
        $products = Product::search(request('search'));

        $products
            ->where('enabled', true)
            ->whereIn('visibility', [
                ProductVisibility::valueForKey(ProductVisibility::SEARCH),
                ProductVisibility::valueForKey(ProductVisibility::CATALOG_AND_SEARCH),
            ])
            ->query(
                fn(Builder $query): Builder => $query
                    ->with('options', function (HasMany $query) {
                        /** @var ProductOption $query */
                        $query->whereStatus(ProductStatus::IN_STOCK);

                        foreach (['minPrice' => '>=', 'maxPrice' => '<='] as $option => $operator)
                            if (request()->exists($option))
                                $query->where(
                                    'price',
                                    $operator,
                                    request($option)
                                );

                        foreach (['type', 'color', 'version'] as $option)
                            if (request()->exists($option))
                                $query->where(
                                    $option,
                                    request($option)
                                );

                        return $query;
                    })
                    ->with('categories', function (BelongsToMany $query) {
                        /** @var Category $query */
                        if (request()->exists('category'))
                            $query->where(
                                'categories.id',
                                request('category')
                            );

                        return $query;
                    })
            );

        return $products;
    }

    /**
     * @return Builder
     */
    protected function catalog(): Builder
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

        foreach (['minPrice' => '>=', 'maxPrice' => '<='] as $option => $operator)
            if (request()->exists($option))
                $products->whereHas(
                    'options',
                    fn(Builder $query): Builder => $query->where(
                        'price',
                        $operator,
                        request($option)
                    )
                );

        foreach (['type', 'color', 'version'] as $option)
            if (request()->exists($option))
                $products->whereHas(
                    'options',
                    fn(Builder $query): Builder => $query->where(
                        $option,
                        request($option)
                    )
                );

        if (request()->exists('category'))
            $products->whereHas(
                'categories',
                fn(Builder $query): Builder => $query->where(
                    'categories.id',
                    request('category')
                )
            );

        return $products;
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
