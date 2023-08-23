<?php

namespace App\Livewire;

use Livewire\Component;

class BrandsLogos extends Component
{
    public $activeCategory = null;

    public function setActiveCategory($category)
    {
        $this->activeCategory = $category;
    }

    public function render()
    {
        return view('livewire.brands-logos');
    }
}