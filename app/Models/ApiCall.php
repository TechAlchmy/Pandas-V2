<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCall extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public $timestamps = false;

    protected $casts = [
        'request' => 'array',
        'response' => 'array'
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
        return $this->allow_retry;
    }
}
