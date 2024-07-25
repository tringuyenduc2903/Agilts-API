<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewFileRequest;
use Illuminate\Support\Str;

class ReviewFileController extends Controller
{
    /**
     * @param ReviewFileRequest $request
     * @return array
     */
    public function __invoke(ReviewFileRequest $request): array
    {
        $image = $request->file('image');

        $mime_type = explode('/', $image->getMimeType());

        $file_name = sprintf(
            '%s_%s_%s.%s',
            $request->user()->id,
            str_replace([' ', ':', '-'], '_', now()),
            strtolower(Str::random(15)),
            end($mime_type)
        );

        $image->storePubliclyAs(
            config('filesystems.disks.review.root'),
            $file_name
        );

        return ['file_name' => $file_name];
    }
}
