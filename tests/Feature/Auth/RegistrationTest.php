<?php

use App\Livewire\Resources\AuthResource\Forms\RegisterForm;
use App\Models\User;
use App\Providers\RouteServiceProvider;

use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Livewire\livewire;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    livewire(RegisterForm::class)
        ->set('data', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->call('register')
        ->assertRedirect('/');

    assertAuthenticatedAs(User::first());
});
