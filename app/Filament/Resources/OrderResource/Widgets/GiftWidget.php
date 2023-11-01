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
    protected static string $view = 'livewire.gift-card';

    public ?Model $record = null;

    public $showDownload = true;

    protected int | string | array $columnSpan = 2;

    protected static ?int $sort = 1;


    public function render(): View
    {
        return view(static::$view, [
            'orderQueue' => $this->record?->orderQueue,
            'size' => 'sm',
        ]);
    }

    public function downloadGiftCard($downloadKey = 0)
    {
        return response()->streamDownload(function () use ($downloadKey) {
            echo Pdf::loadHtml(
                Blade::render('livewire.gift-card-download', [
                    'gift'  => $this->record->orderQueue->gifts[$downloadKey]
                ])
            )->stream();
        }, mt_rand(10000000000000, 99999999999999) . '.pdf');
    }
}
