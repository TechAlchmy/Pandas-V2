<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Resources\OrganizationInvitationResource\Pages\AcceptInvitation;
use Illuminate\Support\Facades\Route;

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
