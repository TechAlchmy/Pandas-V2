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
        'fetched_at' => 'datetime',
        'gifts' => 'array',
        'order_status' => BlackHawkOrderStatus::class
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeFlagged($query)
    {
        return $query->where('created_at', '<=', now()->subDay())
            ->where(function ($q) {
                $q->where('is_order_placed', true)
                    ->where('order_status', '<>', BlackHawkOrderStatus::Complete);
            })->orWhere('is_order_placed', false);
    }

    public function resetFlag(): void
    {
        if (!$this->allowResetFlag()) {
            return;
        }

        $freshRecord = [
            'is_current' => false,
            'order_status' => BlackHawkOrderStatus::Default,
            'fetched_at' => null,
            'gifts' => null,
            'created_at' => now(),
        ];

        if (!$this->is_order_placed) {
            $freshRecord['request_id'] = null;
            $freshRecord['attempted_at'] = null;
        }


        $this->update($freshRecord);
    }

    public function allowResetFlag(): bool
    {
        // If we already got the gifts, it means black hawk charged us money, so we can't allow resetting flag. Otherwise we will be charged twice.
        return empty($this->gifts) && $this->created_at < now()->subDay();
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
        if (in_array($this->order_status, BlackHawkOrderStatus::complete())) {
            $append = ' ✔';
        }

        return $this->order_status->value . $append;
    }
}
