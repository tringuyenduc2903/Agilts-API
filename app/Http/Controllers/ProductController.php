<?php

namespace App\Http\Controllers;

use App\Enums\OptionStatus;
use App\Enums\ProductVisibility;
use App\Models\Option;
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

        $products->query(function (Builder $query) {
            $this->withs($query);
        });

        if (request()->exists(['sortColumn', 'sortDirection']))
            match (request('sortColumn')) {
                'name' => $products->orderBy(
                    request('sortColumn'),
                    request('sortDirection')
                ),
                default => null,
            };
        else if (request()->exists('sortColumn'))
            match (request('sortColumn')) {
                'latest' => $products->latest(),
                'oldest' => $products->oldest(),
                default => null,
            };

        if (request()->exists('product_type'))
            request()->merge([
                'product_type' => [
                    trans('Motor cycle', locale: 'vi'),
                    trans('Square parts', locale: 'vi'),
                    trans('Accessories', locale: 'vi'),
                ][request('product_type')],
            ]);

        $this->productFilter($products);

        if (request()->exists('option_type'))
            request()->merge([
                'option_type' => [
                    trans('New product', locale: 'vi'),
                    trans('Used product', locale: 'vi'),
                    trans('Refurbished product', locale: 'vi'),
                ][request('option_type')],
            ]);

        foreach ([
                     'option_type' => 'type',
                     'color' => 'color',
                     'version' => 'version',
                 ] as $key => $column)
            if (request()->exists($key))
                $products->where("options.$column", request($key));

        if (request()->exists('category'))
            $products->where(
                'categories.id',
                request('category')
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
     * @param Builder|\Laravel\Scout\Builder $query
     * @return void
     */
    protected function productFilter(Builder|\Laravel\Scout\Builder $query): void
    {
        foreach ([
                     'product_type' => 'type',
                     'manufacturer' => 'manufacturer',
                 ] as $key => $column)
            if (request()->exists($key))
                $query->where($column, request($key));
    }

    /**
     * @return Builder
     */
    protected function catalog(): Builder
    {
        $products = Product::whereEnabled(true)
            ->whereIn(
                'visibility', [
                ProductVisibility::CATALOG,
                ProductVisibility::CATALOG_AND_SEARCH,
            ])
            ->whereHas(
                'options',
                function (Builder $query) {
                    /** @var Option $query */
                    return $query->whereStatus(OptionStatus::IN_STOCK);
                }
            );

        $this->withs($products);

        if (request()->exists(['sortColumn', 'sortDirection']))
            match (request('sortColumn')) {
                'name' => $products->orderBy(
                    request('sortColumn'),
                    request('sortDirection')
                ),
                'price' => $products->orderBy(
                    'options_min_price',
                    request('sortDirection')
                ),
                default => null,
            };
        else if (request()->exists('sortColumn'))
            match (request('sortColumn')) {
                'review' => $products->orderByDesc('reviews_avg_rate'),
                'latest' => $products->latest(),
                'oldest' => $products->oldest(),
                default => null,
            };

        foreach (['minPrice' => '>=', 'maxPrice' => '<='] as $option => $operator)
            if (request()->exists($option))
                $products->having(
                    'options_min_price',
                    $operator,
                    request($option)
                );

        $this->productFilter($products);

        foreach ([
                     'option_type' => 'type',
                     'color' => 'color',
                     'version' => 'version',
                 ] as $key => $column)
            if (request()->exists($key))
                $products->whereHas(
                    'options',
                    fn(Builder $query): Builder => $query->where($column, request($key))
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
     * @param string $product_id
     * @return Product
     */
    public function show(string $product_id): Product
    {
        return Product::with('seo')
            ->withCount('reviews')
            ->withAvg('reviews', 'rate')
            ->orWhere('id', $product_id)
            ->orWhere('search_url', $product_id)
            ->whereEnabled(true)
            ->firstOrFail();
    }
}
