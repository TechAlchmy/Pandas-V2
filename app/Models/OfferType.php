<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferType extends Model
{
    use HasFactory;
    use InteractsWithAuditable;
    use SoftDeletes;

    public function organizationOfferTypes()
    {
        return $this->hasMany(OrganizationOfferType::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_offer_types')
            ->withPivot(['is_active'])
            ->withTimestamps();
    }
}
