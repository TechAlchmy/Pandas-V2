<?php

namespace App\Jobs;

use App\Enums\DiscountVoucherTypeEnum;
use App\Models\Brand;
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
                'redemption_info' => $product['redemptionInfo'] ?? null,
                'brand_id' => $this->resolveBrand($product['parentBrandName']),
                'terms' => $product['termsAndConditions']['text'],
                'bh_min' => $product['valueRestrictions']['minimum'] ?? null,
                'bh_max' => $product['valueRestrictions']['maximum'] ?? null,
                'code' => $product['contentProviderCode'],
                'amount' => !empty($product['valueRestrictions']['exclusivelyAllowedValues'])
                    ?  array_map(fn ($val) => $val * 100, $product['valueRestrictions']['exclusivelyAllowedValues'])
                    : $this->convertMinMaxToRange($product['valueRestrictions']['minimum'], $product['valueRestrictions']['maximum']),
            ];

            $voucherType = DiscountVoucherTypeEnum::DefinedAmountsGiftCard;

            $commonFields = [
                'slug' => Str::slug($product['productName'] . ' ' . mt_rand(100000, 999999)),
                'voucher_type' => $voucherType,
                'is_active' => false,
                'is_approved' => false,
                'cta_text' => $voucherType->getDefaultLabel(),
                'is_bhn' => true
            ];

            if (Discount::where('code', $fieldsFromApi['code'])->doesntExist()) {
                Discount::create(array_merge($fieldsFromApi, $commonFields));
            }
            
            // TODO: If we have some product that is missing from the API, we need to disable it.
            // TODO: If we have a disabled product that is present in their catalog, we need to enable it.
            // TODO: If we have a product that is present in their catalog, but the details are different, we need to update it.

            // TODO: Image is saved in a seperate table, so need to add seperately. It is received from $product['productImage]
        });
    }

    private function resolveBrand($brandName): int
    {
        $brand = Brand::where('name', 'ilike', $brandName)->first();
        if ($brand) {
            return $brand->id;
        } else {
            return Brand::create([
                'name' => $brandName,
                'link' => 'Please_Replace_This_'. time() . '_' . mt_rand(100000,999999), // This is get around string to fill not null condition
                'slug' => Str::slug($brandName),
                'is_active' => true
            ])->id;
        }
    }

    private function convertMinMaxToRange($min, $max): array
    {
        $arr = [$min, $max];
        return array_map(fn ($val) => $val * 100, $arr);
        // TODO: 1, 100 => 1, 5, 10, 20, 50, 100
        // 1,100 => min, q1, median, q3, max
    }
}
