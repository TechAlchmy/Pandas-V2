<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\ApiCall;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class GiftWidget extends Widget
{
    // protected static string $view = 'filament.resources.order-resource.widgets.gift-widget';
    protected static string $view = 'livewire.gift-card';

    public ?Model $record = null;

    protected int | string | array $columnSpan = 2;

    protected static ?int $sort = 1;

    // protected function getViewData(): array
    // {
    //     return $this->record?->orderQueue?->toArray();
    // }

    public function render(): View
    {
        return view(static::$view, ['orderQueue' => $this->record?->orderQueue, 'size' => 'sm']);
    }
}
