<?php

namespace App\Filament\Widgets;

use App\Models\Order;
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
        return [
            Stat::make('Revenue', $revenue),
            Stat::make('New Customers', $usersCount),
            Stat::make('Orders', $ordersCount),
        ];
    }
}
