<?php

use App\Services\CartService;
use App\Services\RecentlyViewedService;

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
