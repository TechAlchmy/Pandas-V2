<?php

namespace App\Enums;

enum DiscountVoucherTypeEnum: int
{
    case GetCode = 0;
    case GoToSite = 1;
    case AddToCart = 2;
    case RedeemNow = 3;

    public function getLabel(): ?string
    {
        return str($this->name)
            ->snake()
            ->replace('_', ' ')
            ->title();
    }
}
