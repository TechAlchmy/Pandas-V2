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
        'response' => 'array'
    ];

    public static function disabledApiButton(): bool
    {
        return Carbon::parse(static::orderBy('id', 'desc')->first()?->created_at)->diffInSeconds(now()) < 15;
    }
}
