<?php

use Illuminate\Routing\UrlGenerator;

if (!function_exists('formatPrice')) {
    /**
     * @param float $price
     * @return string
     */
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
    /**
     * @param string $sort_path
     * @return string
     */
    function productImageUrl(string $sort_path): string
    {
        return app(UrlGenerator::class)
            ->assetFrom(
                config('filesystems.disks.product.url'),
                $sort_path,
                app()->environment('production')
            );
    }
}
