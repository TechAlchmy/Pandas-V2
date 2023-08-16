<?php

namespace Tests\Feature\Livewire\Resources\AuthResource\Forms;

use App\Livewire\Resources\AuthResource\Forms\RegisterForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class RegisterFormTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(RegisterForm::class)
            ->assertStatus(200);
    }
}
