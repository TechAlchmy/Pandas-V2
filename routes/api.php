<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// TODO: This is for test right now. This will be removed once test is complete
Route::post('blackhawk', function (Request $request) {

    if (request('type' === 'get')) {
        App\Jobs\GetOrderStatus::dispatch();
    } else {
        App\Jobs\ProcessOrderQueue::dispatch();
    }

    dd('done');
    // App\Services\BlackHawkService::bulkOrder( App\Models\Order::orderBy('id', 'desc')->first());

    // App\Services\BlackHawkService::order( App\Models\Order::orderBy('id', 'desc')->first());
    // App\Jobs\FetchBlackHawk::dispatch();
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
