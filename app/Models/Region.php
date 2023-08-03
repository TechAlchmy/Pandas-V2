<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory;
    use InteractsWithAuditable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'areas',
    ];

    protected $casts = [
        'areas' => 'array',
    ];

    public function brands()
    {
        return $this->hasMany(BrandRegion::class);
    }
}
