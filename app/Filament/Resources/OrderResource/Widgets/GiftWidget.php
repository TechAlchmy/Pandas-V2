<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\ApiCall;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class GiftWidget extends Widget
{
    protected static string $view = 'filament.resources.order-resource.widgets.gift-widget';

    public ?Model $record = null;

    protected int | string | array $columnSpan = 2;

    protected static ?int $sort = 1;

    protected function getViewData(): array
    {
        return ApiCall::where('success', true)
            ->where('order_id', $this->record->id)
            ->whereNotNull('response')
            ->value('response');
    }
    public function render(): View
    {
        return view(static::$view, ['data' => $this->getViewData()]);
    }
}
