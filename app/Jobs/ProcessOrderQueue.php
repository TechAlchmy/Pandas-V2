<?php

namespace App\Jobs;

use App\Models\OrderQueue;
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

    protected $limit = 3;
    /**
     * Create a new job instance.
     */
    public function __construct(int $limit)
    {
        $this->limit = $limit;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orderQueues = OrderQueue::where('is_order_placed', false)->get();
        $orderQueues->each(function ($orderQueue) {
            $orderQueue->start();

            $status = BlackHawkService::bulkOrder($orderQueue->order);

            $orderQueue->stop($status);
        });
    }
}
