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
                ->with('orderDetailRefund'))
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
            ])
            ->filters([
                Tables\Filters\Filter::make('has_unreso_refund_request')
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('resolve_refund')
                    ->visible(fn ($record) => $record->order_detail_refund_exists && ! $record->is_refund_request_approved)
                    ->infolist([
                        Infolists\Components\TextEntry::make('quantity')
                            ->label('Quantity to Refund'),
                        Infolists\Components\TextEntry::make('note'),
                        Infolists\Components\TextEntry::make('estimated_amount_refunded')
                            ->getStateUsing(function ($record) {
                                $record->quantity = $record->orderDetailRefund->quantity;
                                return $record->total / 100;
                            })
                            ->money('USD'),
                    ])
                    ->modalSubmitActionLabel('Approve')
                    ->registerModalActions([
                        Actions\Action::make('reject')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action(function ($action, $record) {
                                $record->orderDetailRefund->delete();
                                $action->success();
                            })
                            ->successNotificationTitle('Refund Request rejected')
                            ->successNotification(function ($notification, $record) {
                                $this->getOwnerRecord()
                                    ->user
                                    ->notify(new SendUserOrderRefundRejected(
                                        $this->getOwnerRecord()->order_column,
                                        \implode(' - ', [
                                            $record->discount->brand->name,
                                            $record->discount->name,
                                        ]
                                    )));
                                return $notification;
                            }),
                    ])
                    ->action(function ($action, $record) {
                        $record->quantity = $record->orderDetailRefund->quantity;

                        (new CreateCcRefund($record->cardknox_refnum, $record->total / 100))->send();

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
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
