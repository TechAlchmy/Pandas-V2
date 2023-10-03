<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDiscount extends EditRecord
{
    protected static string $resource = DiscountResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getSubheading(): string
    {
        return $this->record->is_bhn
        ? 'This is a BHN Discount and hence allows limited modifications only!'
        : null;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['timed'] = !empty($data['starts_at']);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!$data['timed']) {
            $data['starts_at'] = null;
            $data['ends_at'] = null;
        }
        data_forget($data, 'timed');
        return $data;
    }
}
