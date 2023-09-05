<?php

namespace App\Livewire\Resources\OrderResource\Pages;

use Livewire\Component;

class ViewOrder extends Component
{
    public function render()
    {
        return view('livewire.resources.order-resource.pages.view-order')
            ->layout('components.layouts.guest');
    }
}
