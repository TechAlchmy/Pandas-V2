<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Manager;

class organization extends Model
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
