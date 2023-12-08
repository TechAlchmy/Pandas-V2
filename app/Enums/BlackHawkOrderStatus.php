<?php

namespace App\Enums;

enum BlackHawkOrderStatus: string
{
    case Default = 'Not Attempted'; // This is the default value
    case Cancelled = 'Cancelled';
    case Complete = 'Complete';
    case Declined = 'Declined';
    case Error = 'Error';
    case FundingHold = 'Funding Hold';
    case InProcess = 'In Process';
    case NotAllRecordsFunded = 'Not All Records Funded';
    case NotAllRecordsReversed = 'Not All Records Reversed';
    case Failure = 'Failure';
    case Shipped = 'Shipped'; // This is not valid for our use-case
    case SuccessfullySentToProcessor = 'Successfully Sent To Processor'; // This is not valid for our use-case

    public static function getOptions()
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($type) => [$type->name => $type->value]);
    }

    public static function failed(): array
    {
        return [
            self::Cancelled->value,
            self::Declined->value,
            self::Failure->value
        ];
    }

    public static function pending(): array
    {
        return [
            self::Default->value,
            // self::FundingHold->value,
            self::InProcess->value,
            self::NotAllRecordsFunded->value,
            self::NotAllRecordsReversed->value, // This seems rather odd. Some orders revered some succeeded?
            self::Shipped->value,
            self::SuccessfullySentToProcessor->value,
            self::Error->value,
        ];
    }

    public static function actionRequired(): array
    {
        return [
            self::FundingHold->value,
            self::Declined->value,
            self::Cancelled->value
        ];
    }

    public static function complete(): array
    {
        return [
            self::Complete->value
        ];
    }

    // If isOrderSuccessful is true, then the queue is done. Do nothing else
    public static function isOrderSuccessful($status): ?bool
    {
        return match ($status) {
            self::Complete => true,
            self::Cancelled, self::Declined, self::Error, self::Failure => false,
            default => null
        };
    }
}
