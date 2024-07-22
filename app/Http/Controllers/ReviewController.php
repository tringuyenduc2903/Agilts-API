<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $product_id
     * @param Request $request
     * @return array
     */
    public function __invoke(int $product_id, Request $request): array
    {
        $reviews = Product::findOrFail(request('product_id'))
            ->reviews()
            ->latest()
            ->with('response');

        if ($request->exists('rate'))
            match (request('rate')) {
                '1', '2', '3', '4', '5' => $reviews->whereRate(request('rate')),
                'negative' => $reviews->whereIn('rate', ['1', '2', '3']),
                'positive' => $reviews->whereIn('rate', ['4', '5']),
                'with_image' => $reviews->whereJsonLength('reviews.images', '>', 0),
                default => null,
            };

        $paginator = $reviews->paginate(request('perPage'));

        $paginator->append('parent_preview');

        return $this->customPaginate($paginator);
    }
}
