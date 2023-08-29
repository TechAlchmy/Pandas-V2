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
use Filament\Forms;
use Illuminate\Support\Facades\Blade;
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
                    ->form(function ($record) {
                        $record->loadMissing(['orderDetails.discount']);
                        return [
                            Forms\Components\Actions::make($record->orderDetails
                                ->map(function ($orderDetail) {
                                    $record = $orderDetail->discount;
                                    return match ($orderDetail->discount->cta) {
                                        DiscountCallToActionEnum::GoToSite => Forms\Components\Actions\Action::make($orderDetail->discount->name)
                                            ->url($record->link, shouldOpenInNewTab: true),
                                        default => Forms\Components\Actions\Action::make($orderDetail->discount->name)
                                            ->modalHeading(function ($record) use ($orderDetail) {
                                                return "{$orderDetail->discount?->percentage}% off!";
                                            })
                                            ->modalContent(function ($record) use ($orderDetail) {
                                                return Blade::render("
                                                    <div class='p-8 bg-neutral-100 text-center'>
                                                        <p class='text-2xl font-light'>
                                                            " . $orderDetail->discount?->code . "
                                                        </p>
                                                    </div>
                                                ");
                                            })
                                            ->modalSubmitAction(false)
                                            ->extraModalFooterActions(fn ($action): array => [
                                                $action->makeModalSubmitAction('copyCode', arguments: ['copy' => true]),
                                            ])
                                            ->action(function ($arguments, $record) use ($orderDetail): void {
                                                if ($arguments['copy'] ?? false) {
                                                    $this->js("navigator.clipboard.writeText('{$orderDetail->discount?->code}');");

                                                    Notification::make()
                                                        ->title('Code copied successfully')
                                                        ->success()
                                                        ->send();
                                                }
                                            }),
                                    };
                                })
                                ->all()),
                        ];
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
