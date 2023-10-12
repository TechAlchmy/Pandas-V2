<?php

namespace App\Jobs;

use App\Enums\DiscountVoucherTypeEnum;
use App\Mail\ApprovalRequiredMail;
use App\Models\ApiCall;
use App\Models\Brand;
use App\Models\Discount;
use App\Models\Setting;
use App\Notifications\ApprovalRequired;
use App\Services\BlackHawkService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class FetchBlackHawk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ?string $previousReq;
    /**
     * Create a new job instance.
     */
    public function __construct(?string $previousReq = null)
    {
        $this->previousReq = $previousReq;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // The following two lines are to test without calling api
        // $testData = ApiCall::first();
        // $this->updateDiscounts($testData->response['products']);

        $result = BlackHawkService::catalog($this->previousReq);

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

                try {
                    $discount->addMediaFromUrl($product['productImage'])
                        ->preservingOriginal()
                        ->toMediaCollection('featured', 's3');
                } catch (\Spatie\MediaLibrary\MediaCollections\Exceptions\UnreachableUrl $e) {
                }
            } else {
                $existingDiscount = Discount::where('code', $fieldsFromApi['code'])->first();

                // Convert the existingDiscount model and $fieldsFromApi array to collections for comparison.
                $detailsHaveChanged = collect($fieldsFromApi)->reject(function ($value, $key) use ($existingDiscount) {
                    return $existingDiscount[$key] == $value;
                })->isNotEmpty();


                if ($detailsHaveChanged) {
                    // Update the existing discount entry with new details and make it requiring approval.
                    $existingDiscount->update($fieldsFromApi);

                    $existingDiscount->update([
                        'is_active' => false,
                        'is_approved' => false
                    ]);

                    //send email to 
                    // Setting::get('');
                }
            }

            // TODO: If anything needs to be approved, email notification daily after api call to {mail}

        });

        // TODO: If we have some product that is missing from the API, we need to disable it.
        $apiProductCodes = collect($products)->pluck('contentProviderCode')->toArray();
        Discount::whereNotIn('code', $apiProductCodes)->where('is_active', true)->update(['is_active' => false]);

        $receiver = Setting::get('notification_email');
        Notification::route('mail', $receiver)->notify(new ApprovalRequired());
    }

    private function resolveBrand($product): int
    {
        $brand = Brand::where('name', 'ilike', $product['parentBrandName'])->first();
        if ($brand) {
            return $brand->id;
        } else {
            $brand = Brand::create([
                'name' => $product['parentBrandName'],
                'link' => BlackHawkService::DUMMY_URL_PREFIX . time() . '_' . mt_rand(100000, 999999), // This is get around string to fill not null condition
                'slug' => Str::slug($product['parentBrandName']),
                'is_active' => true
            ]);

            if (!empty($product['logoImage'])) {
                $brandImage = str_replace('xsmall', 'xlarge', $product['logoImage']);

                $brand->addMediaFromUrl($brandImage)
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
        // This is no longer needed now as we keep range for defined amounts gift cards only
        // 1,100 => min, q1, median, q3, max
    }
}
