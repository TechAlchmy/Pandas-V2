<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Artisan;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;


    protected function getHeaderActions(): array
    {
        $action = \Filament\Actions\Action::make('cache-clear')
            ->icon('heroicon-o-puzzle-piece')
            ->label('Clear Cache')
            ->requiresConfirmation('Are you sure you want to clear cache?')
            ->modalSubmitActionLabel('Clear Cache')
            ->action(function () {
                try {
                    Artisan::call('cache:clear');
                    Notification::make()
                        ->title('Yayyyyy!')
                        ->body('Cache cleared successfully!')
                        ->success()
                        ->send();
                } catch (\Throwable $exception) {
                    Notification::make()
                        ->title('Could not clear cache! Try again!')
                        ->body('Error: ' . $exception->getMessage())
                        ->danger()
                        ->send();
                }
            });


        return [
            $action
        ];
    }
}
