<?php

use Illuminate\Routing\UrlGenerator;

if (!function_exists('formatPrice')) {
    function formatPrice(float $price): string
    {
        return sprintf(
            '%s %s',
            number_format($price, 2, ',', '.'),
            'VND'
        );
    }
}

if (!function_exists('imageUrl')) {
    function imageUrl(string $storage_path, string $path): string
    {
        return app(UrlGenerator::class)->assetFrom($storage_path, $path);
    }
}

if (!function_exists('productImageUrl')) {
    function productImageUrl(string $path): string
    {
        return imageUrl(
            config('filesystems.disks.product.url'),
            $path
        );
    }
}

if (!function_exists('branchImageUrl')) {
    function branchImageUrl(string $path): string
    {
        return imageUrl(
            config('filesystems.disks.branch.url'),
            $path
        );
    }
}

if (!function_exists('reviewImageUrl')) {
    function reviewImageUrl(string $path): string
    {
        return imageUrl(
            config('filesystems.disks.review.url'),
            $path
        );
    }
}

if (!function_exists('pricePreview')) {
    function pricePreview(float $price): array
    {
        return [
            'raw' => $price,
            'preview' => formatPrice($price),
        ];
    }
}

if (!function_exists('imagePreview')) {
    function imagePreview(string $image, string $alt = ''): array
    {
        return [
            'image' => $image,
            'alt' => $alt,
        ];
    }
}
