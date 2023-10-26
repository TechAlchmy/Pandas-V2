<?php

namespace App\Filament\Widgets;

use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDetailRefund;
use App\Models\OrderQueue;
use App\Models\Setting;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use function Filament\Support\format_money;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $revenue = (int) Order::query()->sum('order_total');
        $revenue = format_money($revenue / 100, 'USD');
        $usersCount = User::query()->count();
        $ordersCount = Order::query()->count();

        $arr = [];

        $showCounterStats = Setting::get('show_counter_stats');

        if ($showCounterStats) {

            $flaggedQueuesCount = OrderQueue::flagged()->count();

            $arr[] = Stat::make(
                'Flagged Queues',
                $flaggedQueuesCount
            )->descriptionIcon('heroicon-s-exclamation-circle')
                ->icon('heroicon-o-queue-list')
                ->description("$flaggedQueuesCount needs review!")
                ->color(($flaggedQueuesCount ? 'warning' : 'success'))
                ->url(route('filament.admin.resources.order-queues.index', [
                    'tableFilters[flagged][isActive]' => 1
                ]));

            $flaggedDiscountsCounts = Discount::flagged()->count();

            $arr[] = Stat::make(
                'Unapproved Discounts',
                $flaggedDiscountsCounts
            )->descriptionIcon('heroicon-s-exclamation-circle')
                ->icon('heroicon-o-receipt-percent')
                ->description("$flaggedDiscountsCounts needs review!")
                ->color(($flaggedDiscountsCounts ? 'warning' : 'success'))
                ->url(route('filament.admin.resources.discounts.index', [
                    'tableFilters[is_approved][value]' => 1
                ]));

            $pendingRefundsCount = OrderDetailRefund::flagged()->count();

            $arr[] = Stat::make(
                'Pending Refunds',
                $pendingRefundsCount
            )->descriptionIcon('heroicon-s-exclamation-circle')
                ->icon('heroicon-o-shopping-bag')
                ->description("$pendingRefundsCount pending refunds!")
                ->color(($pendingRefundsCount ? 'warning' : 'success'))
                ->url(route('filament.admin.resources.order-detail-refunds.index', [
                    'tableFilters[approved_at][value]' => 'Pending'
                ]));
        }

        $arr[] = Stat::make('Revenue', $revenue);
        $arr[] = Stat::make('New Customers', $usersCount);
        $arr[] = Stat::make('Orders', $ordersCount);

        return $arr;
    }
}
