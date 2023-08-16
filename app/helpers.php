<?php

use App\Services\RecentlyViewedService;

if (! function_exists('recentlyViewed')) {
    function recentlyViewed(): RecentlyViewedService
    {
        return app(RecentlyViewedService::class);
    }
}
