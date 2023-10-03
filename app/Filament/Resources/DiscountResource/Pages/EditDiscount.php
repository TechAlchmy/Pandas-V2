<?php

namespace App\Filament\Resources\DiscountResource\Pages;

use App\Filament\Resources\DiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditDiscount extends EditRecord
{
    protected static string $resource = DiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getSubheading(): string | null
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

    protected function beforeValidate(): void
    {
        if ($this->data['bh_min'] && $this->data['bh_max'] && $this->data['is_bhn']) {
            $validator = validator($this->data, [
                'amount.*' => "numeric|required|numeric|min:{$this->data['bh_min']}|max:{$this->data['bh_max']}",
            ], [
                'amount.*.min' => "Each amount must be at least {$this->data['bh_min']}.",
                'amount.*.max' => "Each amount may not be greater than {$this->data['bh_max']}.",
                'amount.*.numeric' => 'Each amount must be a number.',
            ]);

            if (
                $validator->fails()
            ) {
                Notification::make()
                    ->danger()
                    ->title($validator->getMessageBag()->first())
                    ->body("")
                    ->persistent()
                    ->send();
    
                $this->halt();
            }
        }
        
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
