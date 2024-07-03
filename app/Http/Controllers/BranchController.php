<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        $branches = Branch::query();

        $filters = ['country', 'province', 'district', 'ward'];

        foreach ($filters as $filter)
            if ($request->exists($filter))
                $branches->whereHas(
                    'address',
                    fn(Builder $query): Builder => $query->where($filter, $request->input($filter))
                );

        $paginator = $branches->paginate($request->input('per_page'));

        return [
            'data' => $paginator->items(),
            'total_pages' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'total' => $paginator->total(),
        ];
    }
}
