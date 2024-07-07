<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdentificationRequest;
use App\Models\Identification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class IdentificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return auth()->user()->identifications;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param IdentificationRequest $request
     * @return JsonResponse
     */
    public function store(IdentificationRequest $request): JsonResponse
    {
        $identification = Identification::make($request->validated());

        auth()->user()->identifications()->save($identification);

        return response()->json('', 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param IdentificationRequest $request
     * @param int $identification_id
     * @return JsonResponse
     */
    public function update(IdentificationRequest $request, int $identification_id): JsonResponse
    {
        auth()->user()->identifications()
            ->findOrFail($identification_id)
            ->update($request->validated());

        return response()->json('');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $identification_id
     * @return JsonResponse
     */
    public function destroy(int $identification_id): JsonResponse
    {
        $identification = auth()->user()->identifications()
            ->findOrFail($identification_id);

        if ((bool)$identification->default === true)
            abort(403, trans('Default :name cannot be deleted.', [
                'name' => trans('identification'),
            ]));

        $identification->delete();

        return response()->json('');
    }
}
