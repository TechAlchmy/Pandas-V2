<?php

namespace App\Concerns;

use App\Models\User;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait InteractsWithAuditable
{
    public static function bootInteractsWithAuditable()
    {
        static::creating(fn ($model) => $model->created_by_id ??= auth()->id());
        static::updating(fn ($model) => $model->updated_by_id = auth()->id());
        if (method_exists(static::class, 'bootSoftDeletes')) {
            static::deleting(fn ($model) => $model->update(['deleted_by_id' => auth()->id()]));
            static::restoring(fn ($model) => $model->deleted_by_id = null);
        }
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class);
    }
}
