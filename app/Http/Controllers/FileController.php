<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function uploadImage(Request $request): array
    {
        Validator::make($request->all(), [
            'path' => [
                'required',
                'string',
                Rule::in(['review']),
            ],
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png',
                'mimetypes:image/jpeg,image/png',
                'max:2048',
            ],
        ])->validated();

        /** @var UploadedFile $image */
        $image = request('image');

        $file_name = sprintf(
            '%s_%s.%s',
            str_replace([' ', ':', '-'], '_', now()),
            strtolower(Str::random(15)),
            explode('/', $image->getMimeType())[1]
        );

        $image->storeAs(
            request('path'),
            "{$request->user()->id}/$file_name"
        );

        return ['file_name' => $file_name];
    }

    /**
     * @param string $path
     * @param string $image_name
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function image(string $path, string $image_name, Request $request): BinaryFileResponse
    {
        if (!in_array($path, ['review']))
            abort(404);

        $path = sprintf(
            '%s/%s/%s',
            config("filesystems.disks.$path.root"),
            $request->user()->id,
            $image_name
        );

        if (!File::exists($path))
            abort(404);

        return response()->file($path);
    }
}
