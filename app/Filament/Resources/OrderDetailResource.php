<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderDetailResource\Pages;
use App\Models\OrderDetail;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class OrderDetailResource extends Resource
{
    protected static ?string $model = OrderDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'E-Commerce';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id'),
                Forms\Components\TextInput::make('discount_id'),
                Forms\Components\TextInput::make('user_id'),
                Forms\Components\TextInput::make('purchase_date')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id'),
                Tables\Columns\TextColumn::make('discount_id'),
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('purchase_date'),
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
            'index' => Pages\ListOrderDetails::route('/'),
            'create' => Pages\CreateOrderDetail::route('/create'),
            'edit' => Pages\EditOrderDetail::route('/{record}/edit'),
        ];
    }
}
