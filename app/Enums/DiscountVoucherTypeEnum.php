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
}
