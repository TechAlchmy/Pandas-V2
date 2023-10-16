<?php

namespace App\Enums;

enum BlackHawkOrderStatus: string
{
    case Cancelled = 'cancelled';
    case Complete = 'complete';
    case Declined = 'declined';
    case Error = 'error';
    case FundingHold = 'funding_hold';
    case InProcess = 'in_process';
    case NotAllRecordsFunded = 'not_all_records_funded';
    case NotAllRecordsReversed = 'not_all_records_reversed';
    case Failure = 'failure';
    case Shipped = 'shipped';
    case SuccessfullySentToProcessor = 'successfully_sent_to_processor';
}
