<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
    ];

    public function discount()
    {
        return $this->hasMany(Discount::class);
    }
}
