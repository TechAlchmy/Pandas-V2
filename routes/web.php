<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Pages\Checkout;
use App\Livewire\Pages\OrderSummary;
use App\Livewire\Resources\DealResource\Pages\ListDeals;
use App\Livewire\Resources\DealResource\Pages\ViewDeal;
use App\Livewire\Resources\OrganizationInvitationResource\Pages\AcceptInvitation;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('organization-invitations/{record}/accept', AcceptInvitation::class)
    ->middleware(['signed'])
    ->name('organization-invitations.accept');

Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/checkout', Checkout::class)
    ->middleware(['auth', 'verified'])
    ->name('pages.checkout');
Route::get('/order/{order:uuid}/summary', OrderSummary::class)
    ->middleware(['auth'])
    ->name('pages.order.summary');

Route::get('deals', ListDeals::class)
    ->middleware(['auth', 'verified'])
    ->name('deals.index');
Route::get('deals/{id}', ViewDeal::class)
    ->middleware(['auth', 'verified'])
    ->name('deals.show');
