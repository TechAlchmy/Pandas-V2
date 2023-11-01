<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\ApiCall;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;

class GiftWidget extends Widget
{
    // protected static string $view = 'filament.resources.order-resource.widgets.gift-widget';
    protected static string $view = 'livewire.gift-card';

    public ?Model $record = null;

    public $showDownload = true;

    protected int | string | array $columnSpan = 2;

    protected static ?int $sort = 1;

    // protected function getViewData(): array
    // {
    //     return $this->record?->orderQueue?->toArray();
    // }

    public function render(): View
    {
        return view(static::$view, [
            'orderQueue' => $this->record?->orderQueue,
            'size' => 'sm',
        ]);
    }

    public function downloadGiftCard()
    {
        return response()->streamDownload(function () {
            echo Pdf::loadHtml(
                // Use a different blade for this which is supported by DomPDF
                Blade::render('livewire.gift-card-download', [
                    'gift'  => $this->record->orderQueue->gifts[0]
                ])
            )->stream();
        }, mt_rand(10000000, 99999999) . '.pdf');
    }
}
