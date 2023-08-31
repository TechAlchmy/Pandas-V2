<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountCategory;
use App\Models\DiscountTag;
use App\Models\DiscountType;
use App\Models\FeaturedDeal;
use App\Models\Manager;
use App\Models\OfferType;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Organization;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\VoucherType;
use Illuminate\Database\Seeder;
use Squire\Models\Region;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $regionIds = Region::query()->where('country_id', 'us')->pluck('id');
        $organizations = Organization::factory(10)->sequence(
            $regionIds->random(10)
                ->mapWithKeys(fn ($regionId) => ['region_id' => $regionId])
                ->all(),
        )->create()->each(function ($organization) {
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

        $users = User::factory(50)->create()->each(function ($user) {
            $user->userPreference()->save(UserPreference::factory()->make());
        });
        $offerTypes = OfferType::factory(10)
            ->create()
            ->each(function ($offerType) use ($organizations) {
                $organizations->each(function ($organization) use ($offerType) {
                    $offerType->organizationOfferTypes()
                        ->create([
                            'organization_id' => $organization->getKey(),
                            'is_active' => true,
                        ]);
                });
            });
        $voucherTypes = VoucherType::factory(10)->create();
        collect([
            'Apparel' => [
                'Reebok', 'Sketchers', 'Polo', 'Adidas', 'New Balance',
                'Boss', 'Nike', 'Puma',
            ],
            'Entertainment' => [
                'Netflix', 'Disney Hotstar', 'HBO', 'AppleTV+', 'Spotify',
            ],
            'Groceries' => [
                'Walmart', 'Amazon', 'eBay',
            ],
            'Health & Wellness' => [
                'FitnessPlus', 'Apple Health', 'FitPro',
            ],
            'Travel' => [
                'Express Airway', 'TravelPlus',
            ],
        ])
            ->map(function ($brands, $category) use ($offerTypes, $voucherTypes, $regionIds) {
                $category = Category::factory()->create(['name' => $category]);
                collect($brands)
                    ->each(function ($brand) use ($category, $offerTypes, $voucherTypes, $regionIds) {
                        $brand = Brand::factory()
                            ->state(['region_ids' => $regionIds->all()])
                            ->state(['name' => $brand])
                            ->create();
                        BrandCategory::factory()
                            ->for($brand)
                            ->for($category)
                            ->create();
                        $discounts = Discount::factory(5)->for($brand)->create()->each(function ($discount) use ($offerTypes, $voucherTypes) {
                            $discount->offerTypes()->attach($offerTypes->random());
                            $discount->voucherType()->associate($voucherTypes->random());
                            $discount->save();

                            FeaturedDeal::query()->create(['discount_id' => $discount->getKey()]);
                        });
                    });
            });
        Tag::factory(10)->create();
        DiscountTag::factory(10)->create();
        $discounts = Discount::query()->pluck('id');
        Order::factory(125)
            ->create()
            ->each(function ($order) use ($users, $discounts) {
                foreach (range(1, 6) as $count) {
                    OrderDetail::factory()
                        ->state(['discount_id' => $discounts->random()])
                        ->for($order)
                        ->create();
                }
            });
        $brands = Brand::query()->pluck('id');
        $organizations->each(fn ($organization) => $organization->brands()->attach($brands, ['is_active' => true]));
        DiscountType::factory(10)->create();
    }
}
