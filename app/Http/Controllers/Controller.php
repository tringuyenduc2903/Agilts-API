<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

abstract class Controller
{
    /**
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    protected function customPaginate(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => $paginator->items(),
            'total_pages' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'total' => $paginator->total()
        ];
    }
}
