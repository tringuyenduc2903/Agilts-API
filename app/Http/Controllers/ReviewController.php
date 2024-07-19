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
