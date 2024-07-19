<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): array
    {
        $reviews = $request->exists('product_id')
            ? Product::findOrFail(request('product_id'))->reviews()
            : ProductReview::whereParentType(ProductOption::class);

        $reviews
            ->latest('updated_at')
            ->with('response');

        if ($request->exists('rate'))
            match (request('rate')) {
                '1', '2', '3', '4', '5' => $reviews->whereRate(request('rate')),
                'negative' => $reviews->whereIn('rate', ['1', '2', '3']),
                'positive' => $reviews->whereIn('rate', ['4', '5']),
                'with_image' => $reviews->whereJsonLength('images', '>', 0),
                default => null,
            };

        $paginator = $reviews->paginate(request('per_page'));

        $paginator
            ->setHidden([
                'reviewable_id',
                'reviewable_type',
                'reviewable',
                'parent_id',
                'parent_type',
                'parent',
            ])
            ->append('parent_preview');

        return $this->customPaginate($paginator);
    }
}
