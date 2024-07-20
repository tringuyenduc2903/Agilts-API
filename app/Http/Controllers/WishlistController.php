<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistRequest;
use App\Models\ProductList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        return $request->user()->wishlists;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WishlistRequest $request
     * @return JsonResponse
     */
    public function store(WishlistRequest $request): JsonResponse
    {
        ProductList::create([
            'type' => \App\Enums\ProductList::WISHLIST,
            'option_id' => $request->validated()['version'],
            'customer_id' => $request->user()->id,
        ]);

        return response()->json('', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $wishlist_id
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(int $wishlist_id, Request $request): JsonResponse
    {
        $request->user()->wishlists()
            ->findOrFail($wishlist_id)
            ->delete();

        return response()->json('');
    }
}
