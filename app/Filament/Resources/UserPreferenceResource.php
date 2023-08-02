<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserPreferenceResource\Pages;
use App\Models\UserPreference;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class UserPreferenceResource extends Resource
{
    protected static ?string $model = UserPreference::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id'),
                Forms\Components\TextInput::make('email_notification')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('sms_notification')
                    ->required(),
                Forms\Components\TextInput::make('push_notification')
                    ->required(),
                Forms\Components\TextInput::make('email_marketing')
                    ->email()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('email_notification'),
                Tables\Columns\TextColumn::make('sms_notification'),
                Tables\Columns\TextColumn::make('push_notification'),
                Tables\Columns\TextColumn::make('email_marketing'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListUserPreferences::route('/'),
            'create' => Pages\CreateUserPreference::route('/create'),
            'edit' => Pages\EditUserPreference::route('/{record}/edit'),
        ];
    }
}
