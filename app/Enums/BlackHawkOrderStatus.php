<?php

namespace App\Enums;

enum BlackHawkOrderStatus: string
{
    case Cancelled = 'Cancelled';
    case Complete = 'Complete';
    case Declined = 'Declined';
    case Error = 'Error';
    case FundingHold = 'Funding Hold';
    case InProcess = 'In Process';
    case NotAllRecordsFunded = 'Not All Records Funded';
    case NotAllRecordsReversed = 'Not All Records Reversed';
    case Failure = 'Failure';
    case Shipped = 'Shipped';
    case SuccessfullySentToProcessor = 'Successfully Sent To Processor';

    public static function mustRetryOrder($status): bool
    {
        return in_array($status, [self::Cancelled, self::Declined, self::Error]);
    }

    public static function mustRetryStatus($status): bool
    {
        return !static::mustRetryOrder($status);
    }

    public static function isOrderSuccessful($status): ?bool
    {
        return match ($status) {
            self::Complete => true,
            self::Cancelled, self::Declined, self::Error, self::Failure => false,
            default => null
        };
    }
}
