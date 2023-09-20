<?php

namespace App\Filament\Management\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use function Filament\Support\format_money;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $revenue = (int) Order::query()
            ->whereIn('user_id', User::query()->select('id')
                ->whereBelongsTo(filament()->getTenant())
            )
            ->sum('order_total');
        $revenue = format_money($revenue / 100, 'USD');
        $usersCount = User::query()
            ->whereBelongsTo(filament()->getTenant())
            ->count();
        $ordersCount = Order::query()
            ->whereIn('user_id', User::query()->select('id')
                ->whereBelongsTo(filament()->getTenant())
            )
            ->count();
        return [
            Stat::make('Total spent', $revenue),
            Stat::make('Employees', $usersCount),
            Stat::make('Total Orders', $ordersCount),
        ];
    }
}
