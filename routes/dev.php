<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('artisan/{command}', function ($command) {
    Artisan::call($command, request()->query('parameters') ?? []);
});
