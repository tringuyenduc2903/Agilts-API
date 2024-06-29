<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return auth()->user()->addresses
            ->append('type_preview');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddressRequest $request
     * @return JsonResponse
     */
    public function store(AddressRequest $request): JsonResponse
    {
        $address = Address::make($request->validated());

        auth()->user()->addresses()->save($address);

        return response()->json('', 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $address_id
     * @param AddressRequest $request
     * @return JsonResponse
     */
    public function update(int $address_id, AddressRequest $request): JsonResponse
    {
        auth()->user()->addresses()
            ->findOrFail($address_id)
            ->update($request->validated());

        return response()->json('');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $address_id
     * @return JsonResponse
     */
    public function destroy(int $address_id): JsonResponse
    {
        auth()->user()->addresses()
            ->findOrFail($address_id)
            ->delete();

        return response()->json('');
    }
}
