<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class OrderSummary extends Component
{
    public function render()
    {
        return view('livewire.pages.order-summary')
            ->layout('components.layouts.guest');
    }
}
