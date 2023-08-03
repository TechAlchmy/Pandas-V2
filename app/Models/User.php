<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use InteractsWithAuditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'organization_id',
        'user_preference_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function userPreferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function managedOrganizations()
    {
        return $this->hasMany(Manager::class);

    }
}
