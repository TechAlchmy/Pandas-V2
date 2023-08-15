<?php

namespace Tests\Feature\Livewire\Resources\AuthResource\Forms;

use App\Livewire\Resources\AuthResource\Forms\RequestPasswordResetForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class RequestPasswordResetFormTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(RequestPasswordResetForm::class)
            ->assertStatus(200);
    }
}
