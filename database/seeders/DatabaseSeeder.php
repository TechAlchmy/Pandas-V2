<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\BrandRegion;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountCategory;
use App\Models\DiscountRegion;
use App\Models\DiscountTag;
use App\Models\DiscountType;
use App\Models\Manager;
use App\Models\OfferType;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Organization;
use App\Models\Region;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\VoucherType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Region::factory(10)->create();
        Organization::factory(10)->create()->each(function ($organization) {
            $user = User::factory()
                ->create([
                    'organization_id' => $organization->id,
                    'email' => 'manager' . $organization->getKey() . '@test.com',
                ]);
            $user->managers()->create(['organization_id' => $organization->getKey()]);
            $user->userPreference()->save(UserPreference::factory()->make());
        });
        User::factory(2)
            ->sequence(
                ['email' => 'admin1@test.com'],
                ['email' => 'admin2@test.com'],
            )
            ->admins()
            ->create()
            ->each(function ($user, $index) {
                $user->userPreference()->save(UserPreference::factory()->make());
            });

        User::factory(50)->create()->each(function ($user) {
            $user->userPreference()->save(UserPreference::factory()->make());
        });

        Brand::factory(10)->create();
        Category::factory(10)->create();
        BrandCategory::factory(10)->create();
        BrandRegion::factory(10)->create();
        OfferType::factory(10)->create();
        VoucherType::factory(10)->create();
        Discount::factory(10)->create()->each(function ($discount) {
            $discount->offerTypes()->attach(OfferType::inRandomOrder()->first());
            $discount->voucherType()->associate(VoucherType::inRandomOrder()->first());
            $discount->save();
        });
        Tag::factory(10)->create();
        DiscountCategory::factory(10)->create();
        DiscountRegion::factory(10)->create();
        DiscountTag::factory(10)->create();
        Order::factory(125)->create()->each(function ($order) {
            $user = User::inRandomOrder()->first();
            $order->user()->associate($user);
            $order->save();

            foreach (range(1, 6) as $count) {
                $discount = Discount::inRandomOrder()->first();
                $orderDetail = OrderDetail::factory()->make();
                $orderDetail->discount()->associate($discount);
                $orderDetail->order()->associate($order);
                $order->orderDetails()->save($orderDetail);
            }
        });

        DiscountType::factory(10)->create();
    }
}
