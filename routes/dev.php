<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('artisan/{command}', function ($command) {
    logger()->debug('artisan called', [
        'command' => $command,
        'query' => request()->all(),
    ]);
    Artisan::call($command, request()->query('parameters') ?? []);
});
