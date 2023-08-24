<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DiscountCallToActionEnum: int implements HasLabel
{
    case GetCode = 0;
    case GoToSite = 1;

    public function getLabel(): ?string
    {
        return str($this->name)
            ->snake()
            ->replace('_', ' ')
            ->title();
    }
}
