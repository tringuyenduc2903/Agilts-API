<?php

namespace App\Http\Controllers;

use App\Models\Social;
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
        $social = auth()->user()->socials()->find($social_id);

        if ($social) {
            $social->delete();
        } else {
            $model = Social::class;

            abort(404, "No query results for model [$model] $social_id");
        }

        return response()->json('');
    }
}
