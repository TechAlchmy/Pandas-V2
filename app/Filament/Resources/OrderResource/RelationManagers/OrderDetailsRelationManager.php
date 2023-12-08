<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Resources\BrandResource;
use App\Filament\Resources\DiscountResource;
use App\Http\Integrations\Cardknox\Requests\CreateCcRefund;
use Filament\Forms;
use Filament\Infolists;
use Filament\Tables;
use App\Models\OrderDetail;
use App\Notifications\SendUserOrderRefundApproved;
use App\Notifications\SendUserOrderRefundRejected;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions;
use Filament\Notifications\Notification;

class OrderDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDetails';

    protected static ?string $recordTitleAttribute = 'discount.name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('discount_id')
                    ->relationship('discount', 'name')
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->query(OrderDetail::query()
                ->whereBelongsTo($this->getOwnerRecord())
                ->withExists('orderDetailRefund')
                ->withExists(['orderDetailRefund AS is_refund_request_approved' => function ($query) {
                    $query->whereNotNull('approved_at');
                }])
                ->with('orderDetailRefund', fn ($query) => $query->withTrashed()))
            ->columns([
                Tables\Columns\TextColumn::make('discount.brand.name')
                    ->url(fn (OrderDetail $record) => BrandResource::getUrl('edit', ['record' => $record->discount->brand]))
                    ->searchable()
                    ->label('Brand'),
                Tables\Columns\TextColumn::make('discount.name')
                    ->url(fn (OrderDetail $record) => DiscountResource::getUrl('edit', ['record' => $record->discount]))
                    ->searchable()
                    ->label('Name'),

                Tables\Columns\TextColumn::make('quantity')->label('Quantity'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Item Price')
                    ->getStateUsing(fn ($record) => $record->amount / 100)
                    ->money('USD'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->getStateUsing(fn ($record) => $record->subtotal / 100)
                    ->money('USD'),
                Tables\Columns\TextColumn::make('discount')
                    ->getStateUsing(fn ($record) => $record->discount_public / 100)
                    ->money('USD'),
                Tables\Columns\TextColumn::make('total')
                    ->getStateUsing(fn ($record) => $record->total / 100)
                    ->money('USD'),
                Tables\Columns\TextColumn::make('orderDetailRefund.status_message'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('resolve_refund')
                    ->modalHeading(fn ($record) => \implode(' ', [
                        'Resolve Refund for',
                        $record->discount->brand->name,
                        $record->discount->name,
                    ]))
                    ->visible(fn ($record) => $record->order_detail_refund_exists && ! $record->is_refund_request_approved)
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

                            try {
                                $this->getOwnerRecord()
                                    ->user
                                    ->notify(new SendUserOrderRefundRejected(
                                        $this->getOwnerRecord()->order_column,
                                        \implode(' - ', [
                                            $record->discount->brand->name,
                                            $record->discount->name,
                                        ]
                                    )));
                            } catch (\Throwable $e) {
                                logger()->error($e->getMessage());
                            }

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
                        try {
                            $this->getOwnerRecord()
                                ->user
                                ->notify(new SendUserOrderRefundApproved(
                                    $this->getOwnerRecord()->order_column,
                                    \implode(' - ', [
                                        $record->discount->brand->name,
                                        $record->discount->name,
                                    ]
                                )));
                        } catch (\Throwable $e) {
                            logger()->error($e->getMessage());
                        }
                        return $notification;
                    }),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
