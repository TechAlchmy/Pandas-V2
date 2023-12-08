<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvVar extends Model
{
    use HasFactory;
    use \Sushi\Sushi;

    protected function getRows()
    {
        return collect($_ENV)
            ->map(fn ($env, $key) => [
                'key' => $key,
                'value' => $env,
            ])
            ->values()
            ->all();
    }
}
