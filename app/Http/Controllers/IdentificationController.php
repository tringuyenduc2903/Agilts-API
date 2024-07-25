<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdentificationRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IdentificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        return $request->user()->identifications;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param IdentificationRequest $request
     * @return JsonResponse
     */
    public function store(IdentificationRequest $request): JsonResponse
    {
        $request->user()->identifications()->create(
            $request->validated()
        );

        return response()->json('', 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $identification_id
     * @param IdentificationRequest $request
     * @return JsonResponse
     */
    public function update(int $identification_id, IdentificationRequest $request): JsonResponse
    {
        $request->user()->identifications()
            ->findOrFail($identification_id)
            ->update($request->validated());

        return response()->json('');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $identification_id
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(int $identification_id, Request $request): JsonResponse
    {
        $identification = $request->user()->identifications()
            ->findOrFail($identification_id);

        if ($identification->default)
            abort(403, trans('Default :name cannot be deleted.', [
                'name' => trans('identification'),
            ]));

        $identification->delete();

        return response()->json('');
    }
}
