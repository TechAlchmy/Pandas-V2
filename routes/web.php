<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Resources\AuthResource\Pages\Register;
use App\Livewire\Resources\AuthResource\Pages\EmailVerificationPrompt;
use App\Livewire\Resources\DealResource\Pages\ListDeals;
use App\Livewire\Resources\DealResource\Pages\ViewDeal;
use App\Livewire\Resources\OrderResource\Pages\CreateOrder;
use App\Livewire\Resources\OrderResource\Pages\ViewOrder;
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

Route::get('verify-email', EmailVerificationPrompt::class)
    ->middleware(['auth'])
    ->name('verification.notice');

Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/orders/create', CreateOrder::class)
    ->middleware(['auth', 'verified'])
    ->name('orders.create');
Route::get('/order/{id}', ViewOrder::class)
    ->middleware(['auth', 'verified'])
    ->name('orders.show');

Route::get('deals', ListDeals::class)
    ->middleware(['auth', 'verified'])
    ->name('deals.index');
Route::get('deals/{id}', ViewDeal::class)
    ->middleware(['auth', 'verified'])
    ->name('deals.show');

Route::get('register', Register::class)
    ->middleware(['guest', 'signed'])
    ->name('register');
