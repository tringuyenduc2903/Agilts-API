<?php

namespace App\Http\Controllers;

use App\Enums\ProductType;
use App\Models\Category;
use App\Models\ProductOption;

class FilterController extends Controller
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
            'data' => ProductOption::groupBy('id', 'color')
                ->pluck('color', 'color'),
        ], [
            'name' => 'model_name',
            'label' => trans('Model name'),
            'data' => ProductOption::groupBy('id', 'model_name')
                ->pluck('model_name', 'model_name'),
        ], [
            'name' => 'category',
            'label' => trans('Category'),
            'data' => Category::pluck('name', 'id'),
        ]];
    }
}
