<?php

namespace App\Enums;

enum DiscountVoucherTypeEnum: int
{
    case FixedDiscountCode = 0;

    case GeneratedDiscountCode = 1;
    case ExternalLink = 2;
    case ExternalApiLink = 3;
    case DefinedAmountsGiftCard = 4; // This is also used for BHN gift cards that has fixed options
    case TopUpGiftCard = 5; // This is also use dfor BHN gift cards that does not have fixed options

    public static function collect()
    {
        return \collect(self::cases());
    }

    public function getLabel(): ?string
    {
        return str($this->name)
            ->snake()
            ->replace('_', ' ')
            ->title();
    }

    public function getDefaultLabel()
    {
        return match ($this) {
            self::FixedDiscountCode, self::GeneratedDiscountCode => 'Get Code',
            self::ExternalApiLink, self::ExternalLink => 'Go to link',
            self::DefinedAmountsGiftCard => 'Add to cart',
            self::TopUpGiftCard => 'Top up Gift Card',
            default => 'Get it now!',
        };
    }
}
