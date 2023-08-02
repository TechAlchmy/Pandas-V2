<?php

namespace App\Observers;

use App\Models\Brand;

class BrandObserver
{
    /**
     * Handle the Brand "creating" event.
     */
    public function creating(Brand $brand): void
    {
        $brand->created_by = auth()->id();
    }

    /**
     * Handle the Brand "updating" event.
     */
    public function updating(Brand $brand): void
    {
        $brand->updated_by = auth()->id();
        $brand->updated_at = now();
    }

    /**
     * Handle the Brand "deleting" event.
     */
    public function deleting(Brand $brand): void
    {
        $brand->deleted_by = auth()->id();
    }

    /**
     * Handle the Brand "restored" event.
     */
    public function restored(Brand $brand): void
    {
        //
    }

    /**
     * Handle the Brand "force deleted" event.
     */
    public function forceDeleted(Brand $brand): void
    {
        //
    }
}
