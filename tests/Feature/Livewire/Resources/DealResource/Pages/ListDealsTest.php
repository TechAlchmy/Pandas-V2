<?php

namespace Tests\Feature\Livewire\Resources\DealResource\Widgets;

use App\Livewire\Resources\DealResource\Widgets\DealFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class DealFilterTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(DealFilter::class)
            ->assertStatus(200);
    }
}
