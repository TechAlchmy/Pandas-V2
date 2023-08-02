<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('auth_level')
                    ->required(),
                Forms\Components\TextInput::make('social_security_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('organization_id'),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip_code'),
                Forms\Components\TextInput::make('country')
                    ->maxLength(255),
                Forms\Components\TextInput::make('profile_picture')
                    ->maxLength(255),
                Forms\Components\TextInput::make('created_by'),
                Forms\Components\TextInput::make('updated_by'),
                Forms\Components\TextInput::make('deleted_by'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('auth_level'),
                Tables\Columns\TextColumn::make('social_security_number'),
                Tables\Columns\TextColumn::make('organization_id'),
                Tables\Columns\TextColumn::make('phone_number'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('zip_code'),
                Tables\Columns\TextColumn::make('country'),
                Tables\Columns\TextColumn::make('profile_picture'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('updated_by'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_by'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
