<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        return $request->user()->socials;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $social_id
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(int $social_id, Request $request): JsonResponse
    {
        $request->user()->socials()
            ->findOrFail($social_id)
            ->delete();

        return response()->json('');
    }
}
