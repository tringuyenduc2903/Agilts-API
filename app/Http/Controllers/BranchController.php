<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $branches = Branch::query();

        $branches->whereHas(
            'addresses',
            fn(Builder $query): Builder => $query
                ->whereType(\App\Enums\Address\Branch::SHOP)
                ->whereDefault(true)
        );

        $filters = ['country', 'province', 'district', 'ward'];

        foreach ($filters as $filter)
            if ($request->exists($filter))
                $branches->whereHas(
                    'addresses',
                    fn(Builder $query): Builder => $query->where($filter, $request->input($filter))
                );

        return $branches->paginate();
    }

    /**
     * Display the specified resource.
     *
     * @param Branch $branch
     * @return Branch
     */
    public function show(Branch $branch): Branch
    {
        return $branch;
    }
}
