<?php

namespace App\Filament\Resources\ApiCallResource\Pages;

use App\Enums\BlackHawkApiType;
use App\Filament\Resources\ApiCallResource;
use App\Jobs\FetchBlackHawk;
use App\Models\ApiCall;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;

class ListApiCalls extends ListRecords
{
    protected static string $resource = ApiCallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('call')->label('Fetch Fresh Catalog Data')
                ->icon('heroicon-o-play')
                ->modalContent(new HtmlString('Are you sure you want to fresh the latest data ?'))
                ->action(fn () => FetchBlackHawk::dispatch())
                ->disabled(ApiCall::disabledApiButton()),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => \Filament\Resources\Components\Tab::make('Order APIs')
                ->query(fn ($query) => $query->where('api', '<>', BlackHawkApiType::Catalog->value)),
            'bulk' => \Filament\Resources\Components\Tab::make()
                ->query(fn ($query) => $query->where('api', BlackHawkApiType::BulkOrder->value)),
            'realtime' => \Filament\Resources\Components\Tab::make()
                ->query(fn ($query) => $query->where('api', BlackHawkApiType::RealtimeOrder->value)),
            'catalog' => \Filament\Resources\Components\Tab::make()
                ->query(fn ($query) => $query->where('api', BlackHawkApiType::Catalog->value))
        ];
    }
}
