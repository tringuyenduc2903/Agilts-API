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

        foreach (['country', 'province', 'district', 'ward'] as $filter)
            if ($request->exists($filter))
                $branches->whereHas(
                    'address',
                    fn(Builder $query): Builder => $query->where($filter, request($filter))
                );

        $paginator = $branches->paginate(request('perPage'));

        return $this->customPaginate($paginator);
    }
}
