<?php

namespace App\Enums;

use App\Enums\Concerns\Options;
use App\Enums\Concerns\Values;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel
{
    use Options;
    use Values;

    case Pending = 'pending';
    case Processing = 'processing';
    case On_Hold = 'on_hold';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';
    case Failed = 'failed';

    public static function isIncomplete($status): bool
    {
        return !in_array($status, [self::Completed, self::Cancelled, self::Refunded, self::Failed]);
    }

    public static function getOptions()
    {
        return collect(self::cases())
            ->mapWithKeys(fn($type) => [$type->name => $type->value]);
    }

    public function getLabel(): ?string
    {
        return str($this->name)->headline();
    }
}
