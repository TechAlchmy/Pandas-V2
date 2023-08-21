<?php

namespace App\Enums;

use App\Enums\Concerns\Values;
use App\Enums\Concerns\Options;

enum PaymentStatus: string
{
    use Options, Values;

    case Pending = 'pending';
    case Paid = 'paid';
    case Refunded = 'refunded';
    case Failed = 'failed';
    case Approved = 'Approved';
}
