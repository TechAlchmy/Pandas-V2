<?php

namespace App\Filament\Management\Resources\FeaturedDealResource\Pages;

use App\Filament\Management\Resources\FeaturedDealResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeaturedDeals extends ListRecords
{
    protected static string $resource = FeaturedDealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
