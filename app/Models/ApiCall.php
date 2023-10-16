<?php

namespace App\Models;

use App\Enums\BlackHawkApiType;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCall extends Model
{
    protected $guarded = [
        'id'
    ];

    public $timestamps = false;

    protected $casts = [
        'request' => 'array',
        'response' => 'array',
        'api' => BlackHawkApiType::class
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function disabledApiButton(): bool
    {
        $lastCall = static::orderBy('id', 'desc')->first();
        return $lastCall
            ? Carbon::parse(static::orderBy('id', 'desc')->first()?->created_at)->diffInSeconds(now()) < 15
            : false;
    }

    public function canRetry(): bool
    {
        return empty($this->previous_request)
            && in_array($this->api, [
                BlackHawkApiType::Catalog->value, BlackHawkApiType::RealtimeOrder->value
            ]);
    }
}
