<?php

namespace Tests\Feature\Livewire\Resources\UserResource\Forms;

use App\Livewire\Resources\UserResource\Forms\EditProfileForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditProfileFormTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(EditProfileForm::class)
            ->assertStatus(200);
    }
}
