<?php

namespace App\Jobs;

use App\Models\ApiCall;
use App\Models\Discount;
use App\Services\BlackHawkService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchBlackHawk implements ShouldQueue
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
        $result = BlackHawkService::api();

        $status = $result['success'];

        $response = $result['response'];

        if ($status) {
            // $this->updateDiscounts($response['products']);
        }
    }

    private function updateDiscounts($products)
    {
        Discount::create([
            'name' => $products['productName'],
            'excerpt' => $products['productDescription'],
            'brand_id' => $this->resolveBrand($products['parentBrandName'])
        ]);
    }

    private function resolveBrand($brandName)
    {
        return 1;
    }
}
