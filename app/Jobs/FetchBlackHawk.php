<?php

namespace App\Jobs;

use App\Enums\DiscountVoucherTypeEnum;
use App\Models\ApiCall;
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
        // The following two lines are to test without calling api
        // $testData = ApiCall::first();
        // $this->updateDiscounts($testData->response['products']);

        $result = BlackHawkService::api();

        if ($result['success']) {
            $this->updateDiscounts($result['response']['products']);
        }
    }

    private function updateDiscounts($products)
    {
        collect($products)->each(function ($product) {

            $voucherType = !empty($product['valueRestrictions']['exclusivelyAllowedValues'])
                ? DiscountVoucherTypeEnum::DefinedAmountsGiftCard
                : DiscountVoucherTypeEnum::TopUpGiftCard;

            $fieldsFromApi = [
                'name' => $product['productName'],
                'excerpt' => $product['productDescription'],
                'redemption_info' => $product['redemptionInfo'] ?? null,
                'brand_id' => $this->resolveBrand($product),
                'terms' => $product['termsAndConditions']['text'],
                'bh_min' => ($product['valueRestrictions']['minimum'] ?? null) * 100,
                'bh_max' => ($product['valueRestrictions']['maximum'] ?? null) * 100,
                'code' => $product['contentProviderCode'],
                'amount' => $voucherType ===  DiscountVoucherTypeEnum::DefinedAmountsGiftCard
                    ?  array_map(fn ($val) => $val * 100, $product['valueRestrictions']['exclusivelyAllowedValues'])
                    : $this->convertMinMaxToRange($product['valueRestrictions']['minimum'], $product['valueRestrictions']['maximum']),
            ];

            $commonFields = [
                'slug' => Str::slug($product['productName'] . ' ' . mt_rand(100000, 999999)),
                'voucher_type' => $voucherType,
                'is_active' => false,
                'is_approved' => false,
                'cta_text' => $voucherType->getDefaultLabel(),
                'is_bhn' => true
            ];

            if (Discount::where('code', $fieldsFromApi['code'])->doesntExist()) {
                $discount = Discount::create(array_merge($fieldsFromApi, $commonFields));

                $discount->addMediaFromUrl($product['productImage']) //starting method
                    // ->withCustomProperties(['mime-type' => 'image/jpeg']) //middle method
                    ->preservingOriginal() //middle method
                    ->toMediaCollection('featured', 's3'); //finishing method
            }

            // TODO: If we have some product that is missing from the API, we need to disable it.
            // TODO: If we have a disabled product that is present in their catalog, we need to enable it.
            // TODO: If we have a product that is present in their catalog, but the details are different, we need to update it.

            // TODO: Image is saved in a seperate table, so need to add seperately. It is received from $product['productImage]
        });
    }

    private function resolveBrand($product): int
    {
        $brand = Brand::where('name', 'ilike', $product['parentBrandName'])->first();
        if ($brand) {
            return $brand->id;
        } else {
            $brand = Brand::create([
                'name' => $product['parentBrandName'],
                'link' => 'Please_Replace_This_' . time() . '_' . mt_rand(100000, 999999), // This is get around string to fill not null condition
                'slug' => Str::slug($product['parentBrandName']),
                'is_active' => true
            ]);

            if (!empty($product['productImage'])) {
                $product['productImage'] = str_replace('xmall', 'xlarge', $product['productImage']);
                $brand->addMediaFromUrl($product['productImage']) 
                    ->preservingOriginal()
                    ->toMediaCollection('logo', 's3');
            }

            return $brand->id;
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
