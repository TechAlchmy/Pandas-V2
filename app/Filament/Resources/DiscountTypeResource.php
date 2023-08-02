<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountTypeResource\Pages;
use App\Models\DiscountType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class DiscountTypeResource extends Resource
{
    protected static ?string $model = DiscountType::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Products';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('discount_id'),
                Forms\Components\TextInput::make('offertype_id'),
                Forms\Components\TextInput::make('created_by'),
                Forms\Components\TextInput::make('updated_by'),
                Forms\Components\TextInput::make('deleted_by'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('discount_id'),
                Tables\Columns\TextColumn::make('offertype_id'),
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
            'index' => Pages\ListDiscountTypes::route('/'),
            'create' => Pages\CreateDiscountType::route('/create'),
            'edit' => Pages\EditDiscountType::route('/{record}/edit'),
        ];
    }
}
