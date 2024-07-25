<?php

namespace App\Http\Controllers;

use App\Enums\ProductList;
use App\Http\Requests\CartRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        return $request->user()->carts()
            ->with([
                'option',
                'option.product',
            ])
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CartRequest $request
     * @return JsonResponse
     */
    public function store(CartRequest $request): JsonResponse
    {
        $request->user()->carts()->create([
            'type' => ProductList::CART,
            'option_id' => $request->validated()['version'],
        ]);

        return response()->json('', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $cart_id
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(int $cart_id, Request $request): JsonResponse
    {
        $request->user()->carts()
            ->findOrFail($cart_id)
            ->delete();

        return response()->json('');
    }
}
