<?php

namespace App\Livewire\Resources\UserResource\Widgets;

use App\Enums\DiscountCallToActionEnum;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderRefund;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Livewire\Component;

class ListOrders extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()
                ->whereBelongsTo(auth()->user()))
            ->recordUrl(fn ($record) => route('orders.show', ['id' => $record->uuid]))
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_status')
                    ->badge()
                    ->colors([
                        'secondary' => 'pending',
                        'secondary' => 'processing',
                        'primary' => 'on hold',
                        'warning' => 'refunded',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('order_total')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->colors([
                        'primary',
                        'secondary' => 'pending',
                        'warning' => 'refunded',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('redeem')
                    ->link()
                    ->visible(fn ($record) => $record->payment_status == PaymentStatus::Approved)
                    ->modalSubmitAction(false)
                    ->infolist(function (Infolist $infolist, $record) {
                        return $infolist
                            ->record($record->loadMissing('orderDetails.discount.brand'))
                            ->schema([
                                Infolists\Components\RepeatableEntry::make('orderDetails')
                                    ->columns(4)
                                    ->hiddenLabel()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('discount.brand.name')
                                            ->url(fn ($record) => route('deals.index', ['filter' => ['brand_id' => $record->discount?->brand_id]]))
                                            ->label('Brand'),
                                        Infolists\Components\TextEntry::make('discount.name')
                                            ->url(fn ($record) => route('deals.show', ['id' => $record->discount?->slug])),
                                        Infolists\Components\TextEntry::make('amount')
                                            ->money('USD'),
                                        Infolists\Components\TextEntry::make('quantity'),
                                    ]),
                            ]);
                    }),
                Tables\Actions\Action::make('refund')
                    ->link()
                    ->visible(fn ($record) => $record->payment_status == PaymentStatus::Approved
                        && now()->isBefore($record->created_at->addWeeks(2)))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if (now()->isAfter($record->created_at->addWeeks(2))) {
                            Notification::make()
                                ->title('Cannot Refund')
                                ->success()
                                ->send();

                            return;
                        }

                        if ($record->payment_status == PaymentStatus::Approved) {
                            OrderRefund::query()
                                ->create([
                                    'order_id' => $record->getKey(),
                                    'amount' => $record->order_total,
                                ]);

                            Notification::make()
                                ->title('Your request to refund this order has been received')
                                ->success()
                                ->send();
                        }
                    }),
            ]);
    }
}
