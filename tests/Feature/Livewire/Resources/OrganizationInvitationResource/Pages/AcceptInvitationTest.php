<?php

namespace Tests\Feature\Livewire\Resources\OrganizationInvitationResource\Pages;

use App\Livewire\Resources\OrganizationInvitationResource\Pages\AcceptInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

use function Pest\Livewire\livewire;

it('can render', function () {
    livewire(AcceptInvitation::class);
})->todo();
