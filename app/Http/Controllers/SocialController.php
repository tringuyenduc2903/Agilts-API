<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return auth()->user()->socials;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $social_id
     * @return JsonResponse
     */
    public function destroy(int $social_id): JsonResponse
    {
        auth()->user()->socials()
            ->findOrFail($social_id)
            ->delete();

        return response()->json('');
    }
}
