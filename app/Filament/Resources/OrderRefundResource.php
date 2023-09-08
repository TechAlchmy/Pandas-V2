<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderRefundResource\Pages;
use App\Filament\Resources\OrderRefundResource\RelationManagers;
use App\Forms\Components\AuditableView;
use App\Models\OrderRefund;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderRefundResource extends Resource
{
    protected static ?string $model = OrderRefund::class;

    protected static ?string $navigationGroup = 'E-Commerce';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('actual_amount')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('order_id')
                    ->label('Order Number')
                    ->required()
                    ->searchable()
                    ->relationship('order', 'id'),
                AuditableView::make(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order Number'),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($record) => $record?->money_amount),
                Tables\Columns\TextColumn::make('actual_amount')
                    ->formatStateUsing(fn ($record) => $record?->money_actual_amount),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListOrderRefunds::route('/'),
            // 'create' => Pages\CreateOrderRefund::route('/create'),
            'edit' => Pages\EditOrderRefund::route('/{record}/edit'),
        ];
    }
}
