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

        collect([
            'Apparel' => [
                'Reebok' => 'https://upload.wikimedia.org/wikipedia/commons/1/11/Reebok_red_logo.svg',
                'Sketchers' => 'https://upload.wikimedia.org/wikipedia/commons/b/b8/Skechers.svg',
                'Polo' => 'https://upload.wikimedia.org/wikipedia/commons/7/72/Polo_Ralph_Lauren_SVG_Logo.svg',
                'Adidas' => 'https://upload.wikimedia.org/wikipedia/commons/2/20/Adidas_Logo.svg',
                'New Balance' => 'https://upload.wikimedia.org/wikipedia/commons/e/ea/New_Balance_logo.svg',
                'Boss' => 'https://upload.wikimedia.org/wikipedia/commons/7/73/Hugo-Boss-Logo.svg',
                'Nike' => 'https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg',
                'Puma' => 'https://upload.wikimedia.org/wikipedia/id/b/b4/Puma_logo.svg',
                'Converse' => 'https://upload.wikimedia.org/wikipedia/commons/3/30/Converse_logo.svg',
            ],
            'Entertainment' => [
                'Netflix' => 'https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg',
                'Disney Hotstar' => 'https://upload.wikimedia.org/wikipedia/commons/1/1e/Disney%2B_Hotstar_logo.svg',
                'HBO' => 'https://upload.wikimedia.org/wikipedia/commons/d/de/HBO_logo.svg',
                'AppleTV+' => 'https://upload.wikimedia.org/wikipedia/commons/2/28/Apple_TV_Plus_Logo.svg',
                'Spotify' => 'https://upload.wikimedia.org/wikipedia/commons/2/26/Spotify_logo_with_text.svg',
                'Apple Music' => 'https://upload.wikimedia.org/wikipedia/commons/9/9d/AppleMusic_2019.svg',
                'E3' => 'https://upload.wikimedia.org/wikipedia/commons/7/76/E3_Logo.svg',
                'Google Play' => 'https://upload.wikimedia.org/wikipedia/commons/7/7a/Google_Play_2022_logo.svg'
            ],
            'Groceries' => [
                'Walmart' => 'https://upload.wikimedia.org/wikipedia/commons/c/ca/Walmart_logo.svg',
                'Amazon' => 'https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg',
                'eBay' => 'https://upload.wikimedia.org/wikipedia/commons/1/1b/EBay_logo.svg',
                'Costco' => 'https://upload.wikimedia.org/wikipedia/commons/5/59/Costco_Wholesale_logo_2010-10-26.svg',
                'Target' => 'https://upload.wikimedia.org/wikipedia/commons/c/c7/Target_%282018%29.svg',
                'Kroger' => 'https://upload.wikimedia.org/wikipedia/en/1/1c/Kroger_%282021%29_logo.svg',
                'Albertsons' => 'https://upload.wikimedia.org/wikipedia/en/6/69/Albertsons_Companies_%28logo%29.svg',
                'Publix' => 'https://upload.wikimedia.org/wikipedia/commons/9/95/Publix_Logo.svg',
            ],
            'Health & Wellness' => [
                'Sweetgreen' => 'https://upload.wikimedia.org/wikipedia/commons/d/dd/Sweetgreen_logo.svg',
                'Soulcycle' => 'https://upload.wikimedia.org/wikipedia/en/7/73/Soulcyclelogo.png',
                'Love Wellness' => 'https://lovewellness.com/cdn/shop/files/logo-lovewellness_470c7a83-0725-48cb-8996-c20fb1560725_208x89.png?v=1689083916',
                'Fitbit' => 'https://upload.wikimedia.org/wikipedia/commons/a/a3/Fitbit_logo16.svg',
                'Headspace' => 'https://upload.wikimedia.org/wikipedia/commons/f/f7/Headspace_text_logo.png',
                'MyFitnessPal' => 'https://upload.wikimedia.org/wikipedia/en/6/63/MyFitnessPal_Logo.png',
                'Nature\'s Way' => 'https://naturesway.com/cdn/shop/files/logo.webp?v=1660589651&width=200',
                'Technogym' => 'https://upload.wikimedia.org/wikipedia/commons/5/53/Technogym_Logo.png',
            ],
            'Travel' => [
                'Expedia Group' => 'https://upload.wikimedia.org/wikipedia/commons/0/0a/Expedia_Group_logo.svg',
                'Airbnb' => 'https://upload.wikimedia.org/wikipedia/commons/6/69/Airbnb_Logo_B%C3%A9lo.svg',
                'American Express' => 'https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg',
                'Disneyland Park' => 'https://upload.wikimedia.org/wikipedia/commons/1/13/Disneyland_Park_Logo.svg',
                'Delta' => 'https://upload.wikimedia.org/wikipedia/commons/d/d1/Delta_logo.svg',
                'US Airways' => 'https://upload.wikimedia.org/wikipedia/commons/a/a6/US_Airways_Logo_2011.svg',
                'Aloha' => 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Aloha_Airlines_Logo.svg',
                'Contiki' => 'https://upload.wikimedia.org/wikipedia/commons/5/5c/Contiki-logo-clean-v2.svg',
            ],
        ])
            ->map(function ($brands, $category) use ($offerTypes, $regionIds) {
                $category = Category::factory()->create(['name' => $category]);
                collect($brands)
                    ->each(function ($logoUrl, $brand) use ($category, $offerTypes, $regionIds) {
                        $brand = Brand::factory()
                            ->state(['region_ids' => $regionIds->all()])
                            ->state(['name' => $brand])
                            ->create();

                        $brand->addMediaFromUrl($logoUrl)->toMediaCollection('logo');

                        BrandCategory::factory()
                            ->for($brand)
                            ->for($category)
                            ->create();
                        $discounts = Discount::factory(5)->for($brand)->create()->each(function ($discount) use ($offerTypes) {
                            $discount->offerTypes()->attach($offerTypes->random());
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
