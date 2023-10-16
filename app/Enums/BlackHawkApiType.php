<?php

namespace App\Enums;

enum BlackHawkApiType: string
{
    case Catalog = 'catalog';
    case RealtimeOrder = 'realtime_order';
    case BulkOrder = 'bulk_order';
    // case CardInfo = 'card_info'; // We are not saving this as this is never logged
}
