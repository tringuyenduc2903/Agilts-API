<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Models\Category;
use App\Models\ProductOption;

class FilterProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(): array
    {
        return [[
            'name' => 'type',
            'label' => trans('Type'),
            'data' => ProductType::values(),
        ], [
            'name' => 'minPrice',
            'label' => trans('Min price'),
            'data' => ProductOption::min('price'),
        ], [
            'name' => 'maxPrice',
            'label' => trans('Max price'),
            'data' => ProductOption::max('price'),
        ], [
            'name' => 'color',
            'label' => trans('Color'),
            'data' => ProductOption::select('color')
                ->distinct()
                ->orderBy('color')
                ->pluck('color', 'color'),
        ], [
            'name' => 'version',
            'label' => trans('Version'),
            'data' => ProductOption::select('version')
                ->distinct()
                ->orderBy('version')
                ->pluck('version', 'version'),
        ], [
            'name' => 'category',
            'label' => trans('Category'),
            'data' => Category::pluck('name', 'id'),
        ]];
    }
}
