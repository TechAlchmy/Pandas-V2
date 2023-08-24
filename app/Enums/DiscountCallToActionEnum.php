<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DiscountCallToActionEnum: int implements HasLabel
{
    case AddToCart = 0;
    case RedeemNow = 1;
    case GetCode = 2;
    case GoToSite = 3;

    public function getLabel(): ?string
    {
        return str($this->name)
            ->snake()
            ->replace('_', ' ')
            ->title();
    }
}
