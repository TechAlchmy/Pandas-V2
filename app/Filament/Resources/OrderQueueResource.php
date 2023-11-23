<?php

namespace App\Filament\Resources;

use App\Enums\BlackHawkOrderStatus;
use App\Filament\Resources\OrderQueueResource\Pages;
use App\Filament\Resources\OrderQueueResource\RelationManagers;
use App\Models\OrderQueue;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class OrderQueueResource extends Resource
{
    protected static ?string $model = OrderQueue::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = 'Utility Management';

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             \Filament\Infolists\Components\TextEntry::make('order_id')
    //                 ->inlineLabel()
    //                 ->label('Order# :'),

    //             \Filament\Infolists\Components\TextEntry::make('attempted_at')
    //                 ->label('BH Order Attempted At :')
    //                 ->dateTime()
    //                 ->inlineLabel(),

    //             \Filament\Infolists\Components\IconEntry::make('is_order_placed')
    //                 ->label('Order Placed ?'),

    //             \Filament\Infolists\Components\TextEntry::make('fetched_at')
    //                 ->label('Card Info Fetched At :'),
    //         ]);
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('order_id')
                    ->inlineLabel()->label('Order#'),

                DatePicker::make('created_at')
                    ->native(false)
                    ->displayFormat('Y-m-d H:i:s')
                    ->inlineLabel()->label('Queue Created At'),

                DatePicker::make('attempted_at')
                    ->native(false)
                    ->displayFormat('Y-m-d H:i:s')
                    ->inlineLabel()->label('BH Order Attempt'),

                DatePicker::make('fetched_at')
                    ->inlineLabel()
                    ->label('Card Fetch Attempt')
                    ->native(false)
                    ->displayFormat('Y-m-d H:i:s'),

                Textarea::make('last_info')
                    ->label('Card Fetch API Response')
                    ->columnSpanFull()
                    ->rows(10),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->state(fn ($record) => $record->order_id ?: '-')
                    ->label('Order#')
                    ->url(fn ($record) => $record->order_id ? route('filament.admin.resources.orders.edit', $record->order_id) : null),

                Tables\Columns\TextColumn::make('order.order_total')
                    ->formatStateUsing(fn ($state) => round($state / 100, 2))
                    // ->formatStateUsing(fn ($state) => collect($state)->sum('amount'))
                    // ->getStateUsing(function ($record) {
                    //     return $record->order->orderDetails->sum('amount');
                    // })
                    ->label('Order Total')
                    ->prefix('$ '),

                Tables\Columns\TextColumn::make('is_current')->label('Queue Status')
                    ->formatStateUsing(fn ($record) => $record->queueState()),

                Tables\Columns\TextColumn::make('attempted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('is_order_placed')
                    ->label('BH Order?')
                    ->formatStateUsing(fn ($record) => ($record->is_order_placed ? 'Yes' : 'No')
                        . ($record->apiCall ? ' (' . $record->apiCall->status_code . ')' : ''))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        true => 'success',
                        default => 'danger'
                    }),

                Tables\Columns\TextColumn::make('order_status')->label('Order Status')
                    ->formatStateUsing(fn ($record) => $record->orderStatus()),
                // TODO: This row should be colored

                Tables\Columns\TextColumn::make('fetched_at')
                    ->label('BH Status Fetched at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

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

            ->recordClasses(fn (Model $record) => match ($record->order_status) {

                BlackHawkOrderStatus::Default => 'bg-gray-100',
                // BlackHawkOrderStatus::Complete => 'bg-green-100',
                BlackHawkOrderStatus::Failure => 'bg-red-100',
                default => null,
            })

            ->defaultSort('id', 'desc')

            ->filters([
                Filter::make('flagged')
                    ->default(false)
                    ->query(fn (Builder $query) => $query->flagged()),
                SelectFilter::make('order_status')
                    ->options(collect(BlackHawkOrderStatus::getOptions()))
                    ->label(''),
            ], layout: \Filament\Tables\Enums\FiltersLayout::AboveContent)

            ->actions([
                Action::make('call_fetch')
                    ->label('Retry')
                    ->icon('heroicon-o-play')
                    ->requiresConfirmation()
                    ->modalContent(new HtmlString('This will move this order back to queue and will start attempting to fetch card details again. Are you sure ?'))
                    ->action(function (Model $record) {
                        if ($record->allowResetFlag()) {
                            $record->resetCardInfoQueue();
                        }
                    })
                    ->visible(function (Model $record) {
                        return $record->allowResetFlag();
                    }),
                Action::make('call_order')
                    ->label('Reorder')
                    ->icon('heroicon-o-shield-exclamation')
                    ->requiresConfirmation()
                    ->modalContent(new HtmlString('This will place a new order! Are you sure ?'))
                    ->action(function (Model $record) {
                        if ($record->allowReorder()) {
                            $record->order->orderQueues()->delete();
                            $record->order->addToQueue();
                        }
                    })
                    ->visible(function (Model $record) {
                        return $record->allowReorder();
                    }),
                Tables\Actions\ViewAction::make(),
            ])

            ->headerActions([
                Tables\Actions\Action::make('refresh')
                    ->action(function ($livewire) {
                        $livewire->js('$wire.$refresh()');
                    }),
            ])

            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            // 'view' => Pages\ViewOrderQueue::route('/{record}'), // This forces a modal to load
            'index' => Pages\ListOrderQueues::route('/'),
            // 'create' => Pages\CreateOrderQueue::route('/create'),
            // 'edit' => Pages\EditOrderQueue::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('apiCall')
            ->when(request('order_id'), fn ($query) => $query->where('order_id', intval(request('order_id'))));
    }
}
