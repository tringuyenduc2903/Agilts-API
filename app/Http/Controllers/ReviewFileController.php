<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReviewFileController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function __invoke(Request $request): array
    {
        Validator::make($request->all(), [
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png',
                'mimetypes:image/jpeg,image/png',
                'max:2048',
            ],
        ])->validated();

        $image = $request->file('image');

        $file_name = sprintf(
            '%s_%s_%s.%s',
            $request->user()->id,
            str_replace([' ', ':', '-'], '_', now()),
            strtolower(Str::random(15)),
            explode('/', $image->getMimeType())[1]
        );

        $image->storePubliclyAs(
            config('filesystems.disks.review.root'),
            $file_name
        );

        return ['file_name' => $file_name];
    }
}
