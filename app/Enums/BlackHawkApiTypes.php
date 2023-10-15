<?php

namespace App\Enums;

use App\Enums\Concerns\Values;
use App\Enums\Concerns\Options;

enum BlackHawkApiTypes: string
{
    case Catalog = 'catalog';
    case RealtimeOrder = 'realtime_order';
    case BulkOrder = 'bulk_order';
}
