<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountTag extends Model
{
    use HasFactory;

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }


}
