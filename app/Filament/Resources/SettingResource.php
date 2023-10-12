<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_amount_limit')
                    ->prefix('$')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('max_retries')
                ->hint('')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('notification_email')
                    ->hint('This is where you will receive notifications to check discount if any needs approval.')
                    ->nullable()
                    ->columnSpanFull()
                    ->email(),

                Forms\Components\Textarea::make('order_processing_message')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),

                Textarea::make('order_success_message')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),

                Textarea::make('order_failed_message')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_amount_limit')
                    ->numeric()
                    ->prefix('$ ')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
