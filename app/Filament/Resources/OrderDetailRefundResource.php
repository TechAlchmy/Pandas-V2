<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderDetailRefundResource\Pages;
use App\Filament\Resources\OrderDetailRefundResource\RelationManagers;
use App\Http\Integrations\Cardknox\Requests\CreateCcRefund;
use App\Models\OrderDetailRefund;
use App\Notifications\SendUserOrderRefundApproved;
use App\Notifications\SendUserOrderRefundRejected;
use Filament\Infolists;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderDetailRefundResource extends Resource
{
    protected static ?string $model = OrderDetailRefund::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';

    protected static ?string $navigationGroup = 'E-Commerce';

    protected static ?string $modelLabel = 'Refund';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('orderDetail.discount.brand.name'),
                Forms\Components\TextInput::make('orderDetail.discount.name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('orderDetail.order.order_column')
                    ->label('Order Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('orderDetail.discount.brand.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orderDetail.discount.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_message'),
                Tables\Columns\TextColumn::make('orderDetail.subtotal')
                    ->getStateUsing(fn ($record) => $record->orderDetail->subtotal / 100)
                    ->money('USD'),
                Tables\Columns\TextColumn::make('orderDetail.total')
                    ->getStateUsing(fn ($record) => $record->orderDetail->total / 100)
                    ->money('USD'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('approved_at')
                    ->label('Is Approved')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('resolve_refund')
                    ->modalHeading(fn ($record) => \implode(' ', [
                        'Resolve Refund for',
                        $record->discount->brand->name,
                        $record->discount->name,
                    ]))
                    ->infolist([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('quantity')
                                    ->getStateUsing(fn ($record) => $record->orderDetailRefund->quantity)
                                    ->label('Quantity to Refund'),
                                Infolists\Components\TextEntry::make('note')
                                    ->getStateUsing(fn ($record) => $record->orderDetailRefund->note),
                                Infolists\Components\TextEntry::make('estimated_amount_refunded')
                                    ->getStateUsing(function ($record) {
                                        $record->quantity = $record->orderDetailRefund->quantity;
                                        return $record->total / 100;
                                    })
                                    ->money('USD'),
                            ]),
                    ])
                    ->modalSubmitActionLabel('Approve')
                    ->extraModalFooterActions(fn ($action) => [
                        $action->makeModalSubmitAction('reject', arguments: ['reject' => true])
                            ->color('danger'),
                    ])
                    ->action(function ($action, $record, $arguments) {
                        if ($arguments['reject'] ?? false) {
                            $record->orderDetailRefund->delete();

                            $this->getOwnerRecord()
                                ->user
                                ->notify(new SendUserOrderRefundRejected(
                                    $this->getOwnerRecord()->order_column,
                                    \implode(' - ', [
                                        $record->discount->brand->name,
                                        $record->discount->name,
                                    ]
                                )));

                            Notification::make()
                                ->success()
                                ->title('Refund request rejected')
                                ->send();

                            return;
                        }

                        $record->quantity = $record->orderDetailRefund->quantity;

                        (new CreateCcRefund($record->order->cardknox_refnum, $record->total / 100))->send();

                        $record->orderDetailRefund->update([
                            'approved_at' => \now(),
                            'approved_by_id' => \auth()->id(),
                        ]);

                        $action->success();
                    })
                    ->successNotificationTitle('Refund Request approved')
                    ->successNotification(function ($notification, $record) {
                        $this->getOwnerRecord()
                            ->user
                            ->notify(new SendUserOrderRefundApproved(
                                $this->getOwnerRecord()->order_column,
                                \implode(' - ', [
                                    $record->discount->brand->name,
                                    $record->discount->name,
                                ]
                            )));
                        return $notification;
                    }),
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
            'index' => Pages\ListOrderDetailRefunds::route('/'),
            'create' => Pages\CreateOrderDetailRefund::route('/create'),
            'edit' => Pages\EditOrderDetailRefund::route('/{record}/edit'),
        ];
    }
}
