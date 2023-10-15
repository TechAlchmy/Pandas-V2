<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderQueue extends Model
{
    use SoftDeletes;

    protected $casts = [
        'attempted_at' => 'datetime'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    // We assume this as one to one relationship becuase all previous queue will be deleted if failed and a new one will be created
    // This means we will always have one and only one active queue

    public function start(): void
    {
        $this->update([
            'attempted_at' => now(),
            'is_current' => true
        ]);
    }

    public function stop(bool $status): void
    {
        $this->update([
            'is_order_placed' => $status,
            'is_current' => false
        ]);
    }
}
