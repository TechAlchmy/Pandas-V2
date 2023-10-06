<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::updated(fn() => Cache::forget("settings_data"));
    }

    public static function get($key): mixed
    {
        return static::settingsData()[$key] ?? null;
    }

    private static function settingsData(): array
    {
        return (array) Cache::remember("settings_data", 60 * 60, function () {
            return Setting::all();
        });
    }

}
