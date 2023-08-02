<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'discount_id',
        'category_id',
    ];
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
