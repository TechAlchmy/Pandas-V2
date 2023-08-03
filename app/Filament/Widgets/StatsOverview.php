<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $numberFormatter = new \NumberFormatter('en_US',  \NumberFormatter::CURRENCY);
        $revenue = (int) Order::query()->sum('order_total');
        $revenue = $numberFormatter->formatCurrency($revenue, 'USD');
        $usersCount = User::query()->count();
        $ordersCount = Order::query()->count();
        return [
            Card::make('Revenue', $revenue),
            Card::make('New Customers', $usersCount),
            Card::make('Orders', $ordersCount),
        ];
    }
}
