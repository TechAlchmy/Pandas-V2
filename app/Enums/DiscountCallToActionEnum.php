<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DiscountCallToActionEnum: int implements HasLabel
{
    case AddToCart = 0;
    case RedeemNow = 1;
    case BuyNow = 2;
    case AddToBag = 3;
    case ViewCode = 4;
    case GetCode = 5;
    case CopyCode = 6;
    case GoToSite = 7;
    case VisitSite = 8;

    public function getLabel(): ?string
    {
        return str($this->name)
            ->snake()
            ->replace('_', ' ')
            ->title();
    }
}
