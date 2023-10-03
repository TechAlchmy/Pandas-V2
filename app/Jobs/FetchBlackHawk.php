<?php

namespace App\Jobs;

use App\Enums\DiscountVoucherTypeEnum;
use App\Models\Discount;
use App\Services\BlackHawkService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

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
            $this->updateDiscounts($response['products']);
        }
    }

    private function updateDiscounts($products)
    {
        collect($products)->each(function ($product) {
            $fieldsFromApi = [
                'name' => $product['productName'],
                'excerpt' => $product['productDescription'],
                'brand_id' => $this->resolveBrand($product['parentBrandName']),
                'terms' => $product['termsAndConditions']['text'],
                'bh_min' => $product['valueRestrictions']['minimum'] ?? null,
                'bh_max' => $product['valueRestrictions']['maximum'] ?? null,
                'bh_options' => $product['valueRestrictions']['exclusivelyAllowedValues'] ?? null,
            ];

            $voucherType = !empty($product['valueRestrictions']['exclusivelyAllowedValues'])
                ? DiscountVoucherTypeEnum::BlackHawkVariableAmountCard
                : DiscountVoucherTypeEnum::BlackHawkFixedAmountCard;
                
            $commonFields = [
                'slug' => Str::slug($product['productName'] . ' ' . mt_rand(100000, 999999)),
                'voucher_type' => $voucherType,
                'is_active' => true,
                'cta_text' => $voucherType->getDefaultLabel(),
            ];

            Discount::create(array_merge($fieldsFromApi, $commonFields));
            // Image is saved in a seperate table, so need to add seperately. It is received from $product['productImage]
        });
    }

    private function resolveBrand($brandName)
    {
        return 1;
        // TODO: This should check if this name ilike exists in our db and return it. else it should create and return a new id using firstOrCreate
    }
}
