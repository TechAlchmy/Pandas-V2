<?php

use App\Services\CartService;
use App\Services\RecentlyViewedService;
use App\Services\SavedProductService;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

if (!function_exists('recentlyViewed')) {
    function recentlyViewed(): RecentlyViewedService
    {
        return app(RecentlyViewedService::class);
    }
}

if (!function_exists('cart')) {
    function cart(): CartService
    {
        return app(CartService::class);
    }
}

if (!function_exists('savedProduct')) {
    function savedProduct(): SavedProductService
    {
        return app(SavedProductService::class);
    }
}

if (!function_exists('getMediaPath')) {
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

if (!function_exists('mainMediaUrl')) {
    function mainMediaUrl()
    {
        return config('panda.cdn');
    }
}

if (!function_exists('barCodeGenerator')) {
    function barCodeGenerator($cardNumber)
    {
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $image = $generator->getBarcode($cardNumber, $generator::TYPE_CODE_128);

        $image = Image::make($image)->encode('webp');
        $resource = $image->stream(null, 100)->detach();

        $filename = 'barcodes/' . Str::random(200) .  time() . '.webp';

        $status = Storage::disk('s3')->put("$filename", $resource, 'public');
        if ($status) {
            $tempUrl = url(Storage::temporaryUrl($filename, now()->addMinutes(5)));
        }

        return ($tempUrl ?? '');
    }
}
