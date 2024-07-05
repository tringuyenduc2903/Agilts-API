<?php

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
