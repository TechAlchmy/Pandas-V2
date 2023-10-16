<?php

namespace App\Jobs;

use App\Enums\BlackHawkOrderStatus;
use App\Models\OrderQueue;
use App\Models\Setting;
use App\Services\BlackHawkService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetOrderStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $limit = Setting::get('bulk_order_batch_size');

        $orderQueues = OrderQueue::with('order')
            ->where('is_order_placed', true)
            ->where(function ($q) {
                $q->whereNull('is_order_success')
                    ->whereIn('order_status', BlackHawkOrderStatus::pending());
            })
            ->orderByRaw("CASE WHEN fetched_at IS NULL THEN 0 ELSE 1 END ASC")->orderBy('fetched_at', 'ASC')
            ->limit($limit)
            ->get();

        $orderQueues->each(function ($orderQueue) {
            BlackHawkService::cardInfo($orderQueue);
        });
    }
}
