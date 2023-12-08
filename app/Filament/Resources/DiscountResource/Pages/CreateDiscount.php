<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDiscount extends CreateRecord
{
    protected static string $resource = DiscountResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!$data['timed']) {
            $data['starts_at'] = null;
            $data['ends_at'] = null;
        }
        data_forget($data, 'timed');
        return $data;
    }
}