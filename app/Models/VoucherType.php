<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherType extends Model
{
    use HasFactory;
    use InteractsWithAuditable;

    protected $fillable = [
        'type',
        'description',
    ];

    public function discount()
    {
        return $this->hasMany(Discount::class);
    }
}
