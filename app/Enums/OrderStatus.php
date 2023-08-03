<?php

namespace App\Enums;

use App\Enums\Concerns\Options;
use App\Enums\Concerns\Values;

enum OrderStatus: string
{
    use Options, Values;

    case Pending = 'pending';
    case Processing = 'processing';
    case On_Hold = 'on_hold';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';
    case Failed = 'failed';
}
