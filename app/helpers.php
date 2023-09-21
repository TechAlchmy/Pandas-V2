<?php

use App\Services\CartService;
use App\Services\RecentlyViewedService;
use App\Services\SavedProductService;

if (! function_exists('recentlyViewed')) {
    function recentlyViewed(): RecentlyViewedService
    {
        return app(RecentlyViewedService::class);
    }
}

if (! function_exists('cart')) {
    function cart(): CartService
    {
        return app(CartService::class);
    }
}

if (! function_exists('savedProduct')) {
    function savedProduct(): SavedProductService
    {
        return app(SavedProductService::class);
    }
}

if (! function_exists('getMediaPath')) {
    function getMediaPath(string $filename = null, string $size = null): string | null
    {
        // $size is not used yet, but it will probably be used in the future
        if (empty($filename)) {
            return null;
        }

        $main = mainMediaUrl();

        if (!$size) {
            return "$main/$filename";
        }

        // When we have size, we must return based on how we have saved the filenames in our db
        return "$main/$filename";

    }
}

if (! function_exists('mainMediaUrl')) {
    function mainMediaUrl()
    {
        return config('panda.cdn');
    }
}
