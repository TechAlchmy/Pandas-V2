<?php

namespace Tests\Feature\Livewire\Resources\UserResource\Forms;

use App\Livewire\Resources\UserResource\Forms\EditProfileForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('renders successfully', function () {
    actingAs($user = User::factory()->create());
    livewire(EditProfileForm::class)
        ->assertStatus(200);
});
