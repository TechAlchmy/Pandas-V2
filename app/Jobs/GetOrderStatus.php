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
            ->where('is_order_success', true)
            ->limit($limit)
            ->get();

        $orderQueues->each(function ($orderQueue) {
            $orderQueue->fetchStatus();

            $status = BlackHawkService::requestStatus($orderQueue->order);

            $orderQueue->stop($status);
        });
    }
}
