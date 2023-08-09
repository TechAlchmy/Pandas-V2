<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $numberFormatter = new \NumberFormatter('en_US',  \NumberFormatter::CURRENCY);
        $revenue = (int) Order::query()->sum('order_total');
        $revenue = $numberFormatter->formatCurrency($revenue, 'USD');
        $usersCount = User::query()->count();
        $ordersCount = Order::query()->count();
        return [
            Stat::make('Revenue', $revenue),
            Stat::make('New Customers', $usersCount),
            Stat::make('Orders', $ordersCount),
        ];
    }
}
