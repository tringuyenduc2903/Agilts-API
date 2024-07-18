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

if (!function_exists('productImageUrl')) {
    function productImageUrl(string $path): string
    {
        return app(UrlGenerator::class)
            ->assetFrom(
                config('filesystems.disks.product.url'),
                $path
            );
    }
}

if (!function_exists('reviewImageUrl')) {
    function reviewImageUrl(string $path): string
    {
        return app(UrlGenerator::class)
            ->assetFrom(
                config('filesystems.disks.review.url'),
                $path
            );
    }
}
