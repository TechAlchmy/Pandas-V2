<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

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
