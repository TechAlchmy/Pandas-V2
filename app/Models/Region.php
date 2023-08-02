<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if(auth()->check()) {
                $model->created_by = auth()->user()->id;
            } else {
                $model->created_by = null; 
            }
        });

        static::updating(function ($model) {
            if(auth()->check()) {
                $model->updated_by = auth()->user()->id;
            } else {
                $model->updated_by = null;
            }
        });
    }

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

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy() {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy() {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}