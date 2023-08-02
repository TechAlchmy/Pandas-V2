<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'slug',
        'uniqid',
        'description',
        'logo',
        'views',
        'status',
    ];

    public function brandCategories()
    {
        return $this->hasMany(BrandCategory::class);
    }

    public function discountCategories()
    {
        return $this->hasMany(DiscountCategory::class);
    }
}
