<?php

namespace App\Enums;

enum DiscountCallToActionEnum: int
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
}
