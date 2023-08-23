<?php

namespace Tests\Feature\Livewire\Resources\DealResource\Forms;

use App\Livewire\Resources\DealResource\Forms\RedeemNowForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class RedeemNowFormTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(RedeemNowForm::class)
            ->assertStatus(200);
    }
}
