<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithAuditable;

    protected $fillable = [
        'name',
        'website',
        'slug',
        'uniqid',
        'phone',
        'email',
        'user_id',
        'region_id',
    ];

    public function managers()
    {
        return $this->hasMany(Manager::class);
    }
}
