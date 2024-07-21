<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Enums\ProductVisibility;
use App\Models\Category;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
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

        $paginator = $products->paginate(request('perPage'));

        return $this->customPaginate($paginator);
    }

    /**
     * @return \Laravel\Scout\Builder
     */
    protected function search(): \Laravel\Scout\Builder
    {
        $products = Product::search(request('search'));

        app()->setLocale('vi');

        $products
            ->where('enabled', true)
            ->whereIn('visibility', [
                ProductVisibility::valueForKey(ProductVisibility::SEARCH),
                ProductVisibility::valueForKey(ProductVisibility::CATALOG_AND_SEARCH),
            ])
            ->query(
                function (Builder $query) {
                    $this->withs($query);
                    $this->sorts($query);
                    $this->filterByPrice($query);

                    $query
                        ->with('options', function (HasMany $query) {
                            /** @var Option $query */
                            $query->whereStatus(ProductStatus::IN_STOCK);

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
                        });
                }
            );

        return $products;
    }

    /**
     * @param Builder $query
     * @return void
     */
    protected function withs(Builder $query): void
    {
        $query->withMin('options', 'price')
            ->withMax('options', 'price')
            ->withCount('reviews')
            ->withAvg('reviews', 'rate');
    }

    /**
     * @param Builder $query
     * @return void
     */
    protected function sorts(Builder $query): void
    {
        if (request()->exists(['sortColumn', 'sortDirection']))
            match (request('sortColumn')) {
                'name' => $query->orderBy(
                    request('sortColumn'),
                    request('sortDirection')
                ),
                'price' => $query->orderBy(
                    'options_min_price',
                    request('sortDirection')
                ),
                default => null,
            };
        else if (request()->exists('sortColumn'))
            match (request('sortColumn')) {
                'review' => $query->orderByDesc('reviews_avg_rate'),
                'latest' => $query->latest(),
                'oldest' => $query->oldest(),
                default => null,
            };
    }

    /**
     * @param Builder $query
     * @return void
     */
    protected function filterByPrice(Builder $query)
    {
        foreach (['minPrice' => '>=', 'maxPrice' => '<='] as $option => $operator)
            if (request()->exists($option))
                $query->having(
                    'options_min_price',
                    $operator,
                    request($option)
                );
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
                    /** @var Option $query */
                    return $query->whereStatus(ProductStatus::IN_STOCK);
                }
            );

        $this->withs($products);
        $this->sorts($products);
        $this->filterByPrice($products);

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
        return Product::withCount('reviews')
            ->withAvg('reviews', 'rate')
            ->findOrFail($product_id);
    }
}
