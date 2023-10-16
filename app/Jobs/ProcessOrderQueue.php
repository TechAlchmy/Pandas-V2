<?php

namespace App\Jobs;

use App\Models\OrderQueue;
use App\Models\Setting;
use App\Services\BlackHawkService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrderQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $limit = Setting::get('bulk_order_batch_size');
        $orderQueues = OrderQueue::with('order.orderDetails')
            ->where('is_order_placed', false)
            ->orderByRaw("CASE WHEN attempted_at IS NULL THEN 0 ELSE 1 END ASC")->orderBy('attempted_at', 'ASC')
            ->limit($limit)
            ->get();

        $orderQueues->each(function ($orderQueue) {
            $noOfItems = $orderQueue->order->orderDetails->sum('quantity');
            if ($noOfItems > 1) {
                BlackHawkService::bulkOrder($orderQueue);
            } else {
                BlackHawkService::realtimeOrder($orderQueue);
            }
        });
    }
}
