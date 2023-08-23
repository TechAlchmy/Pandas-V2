<? php

use App\Livewire\Resources\DealResource\Pages\ViewDeal;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ViewDeal::class)
        ->assertStatus(200);
});
