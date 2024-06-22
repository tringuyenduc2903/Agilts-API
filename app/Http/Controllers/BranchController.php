<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return Branch::paginate();
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
