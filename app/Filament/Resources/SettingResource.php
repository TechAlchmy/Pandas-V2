<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

                Forms\Components\TextInput::make('bulk_order_batch_size')
                    ->hint('How many orders should process every minute? Recommended: 3')
                    ->required()
                    ->numeric(),

                Forms\Components\TagsInput::make('notification_emails')
                    ->hint('This is where you will receive notifications to check discount if any needs approval.')
                    ->nullable()
                    ->nestedRecursiveRules('email')
                    ->splitKeys(['Tab', ' ', ','])
                    ->placeholder('Add email')
                    ->columnSpanFull(),

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

                Toggle::make('show_counter_stats')->hint('Do you want to show counter stats on the dashboard?')

                // TextInput::make('cards_char_limit')
                //     ->label('Deals Card Desc Limit')
                //     ->hint('How many chars should be shown in the deals ? Recommended: 120')
                //     ->integer()
                //     ->minValue(0)
                //     ->required()
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
