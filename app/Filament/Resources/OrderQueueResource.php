<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderQueueResource\Pages;
use App\Filament\Resources\OrderQueueResource\RelationManagers;
use App\Models\OrderQueue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderQueueResource extends Resource
{
    protected static ?string $model = OrderQueue::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\TextEntry::make('order_id')->label('Order#'),

                \Filament\Infolists\Components\TextEntry::make('attempted_at'),

                \Filament\Infolists\Components\IconEntry::make('is_order_placed')
                    ->label('Order Placed?'),

                \Filament\Infolists\Components\IconEntry::make('is_order_success')
                    ->label('Order Successful?')->true()->false(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')->state(fn ($record) => $record->order_id ?: '-')
                    ->url(fn ($record) => $record->order_id ? route('filament.admin.resources.orders.edit', $record->order_id) : null),

                Tables\Columns\TextColumn::make('is_current')->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Running...' : 'Waiting...'),

                Tables\Columns\TextColumn::make('attempted_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_order_placed')->label('Order Placed ?')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_order_success')->label('Order Successful ?')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->paginated([25, 50, 100, 'all'])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'view' => Pages\ViewOrderQueue::route('/{record}'),
            'index' => Pages\ListOrderQueues::route('/'),
            'create' => Pages\CreateOrderQueue::route('/create'),
            // 'edit' => Pages\EditOrderQueue::route('/{record}/edit'),
        ];
    }
}
