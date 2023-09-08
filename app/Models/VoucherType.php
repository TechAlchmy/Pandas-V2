<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherType extends Model
{
    use HasFactory;
    use InteractsWithAuditable;
    use SoftDeletes;

    public function discount()
    {
        return $this->hasMany(Discount::class);
    }

    public function organizationVoucherTypes()
    {
        return $this->hasMany(OrganizationVoucherType::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_voucher_types')
            ->withPivot(['is_active'])
            ->withTimestamps();
    }

    public function scopeForOrganization($query, $organization)
    {
        return $query->when($organization, function ($query, $organization) {
            $query->whereIn('voucher_types.id', OrganizationVoucherType::query()
                ->select('voucher_type_id')
                ->where('is_active', true)
                ->whereBelongsTo($organization));
        });
    }
}
