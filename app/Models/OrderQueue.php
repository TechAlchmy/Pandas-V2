<?php

namespace App\Models;

use App\Enums\BlackHawkOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderQueue extends Model
{
    use SoftDeletes;

    protected $casts = [
        'attempted_at' => 'datetime',
        'order_status' => BlackHawkOrderStatus::class
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function start(string $requestId): void
    {
        $this->update([
            'attempted_at' => now(),
            'is_current' => true,
            'request_id' => $requestId
        ]);
    }

    public function stop(bool $status): void
    {
        $this->update([
            'is_order_placed' => $status,
            'is_current' => false
        ]);
    }

    public function queueState(): string
    {
        if ($this->is_order_placed) {
            return 'Processed ✔';
        }

        if ($this->is_current) {
            return 'Processing...';
        }

        return 'Waiting...';
    }

    public function orderStatus(): string
    {
        $append = '';
        if ($this->is_order_placed) {
            $append = ' ✔';
        }

        return $this->order_status . $append;
    }
}
