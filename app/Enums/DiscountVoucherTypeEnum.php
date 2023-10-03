<?php

namespace App\Enums;

enum DiscountVoucherTypeEnum: int
{
    case FixedDiscountCode = 0;
    case GeneratedDiscountCode = 1;
    case ExternalLink = 2;
    case ExternalApiLink = 3;
    case DefinedAmountsGiftCard = 4;
    case TopUpGiftCard = 5;
    case BlackHawkVariableAmountCard = 6;
    case BlackHawkFixedAmountCard = 7;

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
            self::DefinedAmountsGiftCard, self::BlackHawkFixedAmountCard, self::BlackHawkVariableAmountCard => 'Add to cart',
            self::TopUpGiftCard => 'Top up Gift Card',
            default => 'Get it now!',
        };
    }
}
