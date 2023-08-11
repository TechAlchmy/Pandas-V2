<?php

namespace Tests\Feature\Livewire\Resources\ContactInquiryResource\Forms;

use App\Livewire\Resources\ContactInquiryResource\Forms\ContactUsForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ContactUsFormTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(ContactUsForm::class)
            ->assertStatus(200);
    }
}
