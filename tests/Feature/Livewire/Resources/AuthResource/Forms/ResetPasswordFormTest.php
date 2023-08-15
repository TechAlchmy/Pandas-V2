<?php

namespace Tests\Feature\Livewire\Resources\AuthResource\Forms;

use App\Livewire\Resources\AuthResource\Forms\ResetPasswordForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ResetPasswordFormTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ResetPasswordForm::class)
            ->assertStatus(200);
    }
}
