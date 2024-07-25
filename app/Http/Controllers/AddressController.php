<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        return $request->user()->addresses
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
        $request->user()->addresses()->create(
            $request->validated()
        );

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
        $request->user()->addresses()
            ->findOrFail($address_id)
            ->update($request->validated());

        return response()->json('');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $address_id
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(int $address_id, Request $request): JsonResponse
    {
        $address = $request->user()->addresses()
            ->findOrFail($address_id);

        if ($address->default)
            abort(403, trans('Default :name cannot be deleted.', [
                'name' => trans('address'),
            ]));

        $address->delete();

        return response()->json('');
    }
}
