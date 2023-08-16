<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('returns the intended response when the user is guest', function () {
    get('/')->assertOk();
    get('login')->assertOk();
    get('register')->assertOk();
    get('forgot-password')->assertOk();
    get('help')->assertOk();
    get('deals')->assertOk();
    get('benefits')->assertOk();
    get('search')->assertOk();
    get('search')->assertOk();
    get('verify-email')->assertRedirectToRoute('login');
    get('dashboard')->assertRedirectToRoute('login');
});

it('returns the intended response when the user is authenticated', function () {
    actingAs($user = User::factory()->unverified()->create());
    get('/')->assertOk();
    get('login')->assertRedirect('/');
    get('register')->assertRedirect('/');
    get('forgot-password')->assertRedirect('/');
    get('verify-email')->assertOk();
    get('deals')->assertOk();
    get('benefits')->assertOk();
    get('help')->assertOk();
    get('search')->assertOk();
    get('dashboard')->assertRedirectToRoute('verification.notice');
    $user->update(['email_verified_at' => now()]);
    get('dashboard')->assertOk();
});
