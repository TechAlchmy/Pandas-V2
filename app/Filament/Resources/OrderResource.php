<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'E-Commerce';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id'),
                Forms\Components\TextInput::make('order_status')
                    ->maxLength(255),
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('order_total')
                    ->maxLength(255),
                Forms\Components\TextInput::make('order_subtotal')
                    ->maxLength(255),
                Forms\Components\TextInput::make('order_discount')
                    ->maxLength(255),
                Forms\Components\TextInput::make('order_tax')
                    ->maxLength(255),
                Forms\Components\TextInput::make('payment_method')
                    ->maxLength(255),
                Forms\Components\TextInput::make('payment_status')
                    ->maxLength(255),
                Forms\Components\TextInput::make('created_by'),
                Forms\Components\TextInput::make('updated_by'),
                Forms\Components\TextInput::make('deleted_by'),
                Forms\Components\TextInput::make('order_date')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('order_status'),
                Tables\Columns\TextColumn::make('order_number'),
                Tables\Columns\TextColumn::make('order_total'),
                Tables\Columns\TextColumn::make('order_subtotal'),
                Tables\Columns\TextColumn::make('order_discount'),
                Tables\Columns\TextColumn::make('order_tax'),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('payment_status'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('updated_by'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_by'),
                Tables\Columns\TextColumn::make('order_date'),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }    
}