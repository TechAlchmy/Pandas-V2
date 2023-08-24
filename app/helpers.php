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
