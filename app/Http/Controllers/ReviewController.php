<?php

namespace App\Http\Controllers;

use App\Models\ProductOption;
use App\Models\ProductReview;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): array
    {
        $reviews = ProductReview::with('response')
            ->latest('product_reviews.updated_at')
            ->whereParentType(ProductOption::class);

        if ($request->exists('product_id'))
            $reviews->whereHasMorph(
                'parent',
                ProductOption::class,
                function (Builder $query) {
                    /** @var ProductOption $query */
                    $query->whereProductId(request('product_id'));
                }
            );

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
