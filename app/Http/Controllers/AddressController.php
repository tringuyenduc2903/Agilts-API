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
        return auth()->user()->addresses;
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
        $address = auth()->user()->addresses()->find($address_id);

        if ($address) {
            $address->update($request->validated());
        } else {
            $model = Address::class;

            abort(404, "No query results for model [{$model}] $address_id");
        }

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
        $address = auth()->user()->addresses()->find($address_id);

        if ($address) {
            $address->delete();
        } else {
            $model = Address::class;

            abort(404, "No query results for model [{$model}] $address_id");
        }

        return response()->json('');
    }
}
