<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Option;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): array
    {
        $paginator = $request->user()->reviews()
            ->latest()
            ->paginate(request('per_page'));

        $paginator
            ->setHidden([
                'reviewable_id',
                'reviewable_type',
                'reviewable',
                'parent_id',
                'parent_type',
                'parent',
                'images',
            ])
            ->append('parent_preview');

        return $this->customPaginate($paginator);
    }

    /**
     * Display the specified resource.
     *
     * @param int $review_id
     * @param Request $request
     * @return Review
     */
    public function show(int $review_id, Request $request): Review
    {
        return $request->user()->reviews()
            ->with('response')
            ->findOrFail($review_id)
            ->append('parent_preview');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReviewRequest $request
     * @return JsonResponse
     */
    public function store(ReviewRequest $request): JsonResponse
    {
        $review = Review::make(array_merge(
            $request->validated(), [
            'parent_id' => request('version'),
            'parent_type' => Option::class,
        ]));

        $request->user()->reviews()->save($review);

        return response()->json('', 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $review_id
     * @param ReviewRequest $request
     * @return JsonResponse
     */
    public function update(int $review_id, ReviewRequest $request): JsonResponse
    {
        $request->user()->reviews()
            ->findOrFail($review_id)
            ->update($request->validated());

        return response()->json('');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $review_id
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(int $review_id, Request $request): JsonResponse
    {
        $request->user()->reviews()
            ->findOrFail($review_id)
            ->delete();

        return response()->json('');
    }
}
