<?php

namespace Tests\Feature\Livewire\Resources\AuthResource\Widgets;

use App\Livewire\Resources\AuthResource\Widgets\EmailVerificationPrompt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EmailVerificationPromptTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(EmailVerificationPrompt::class)
            ->assertStatus(200);
    }
}
