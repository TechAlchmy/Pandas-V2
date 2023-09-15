<?php

namespace App\Livewire\Resources\OrderResource\Pages;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Notifications\SendUserOrderRefundInReview;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ViewOrder extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    public $id;

    public function viewInfolist(Infolist $infolist)
    {
        return $infolist
            ->record($this->record)
            ->columns(['default' => 2])
            ->schema([
                Infolists\Components\TextEntry::make('order_status')
                    ->badge(),
                Infolists\Components\TextEntry::make('order_date')
                    ->date(),
                Infolists\Components\TextEntry::make('order_discount')
                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                    ->getStateUsing(fn ($record) => $record->order_discount / 100)
                    ->money('USD'),
                Infolists\Components\TextEntry::make('order_total')
                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                    ->getStateUsing(fn ($record) => $record->order_total / 100)
                    ->money('USD')
                    ->hintActions([
                        Infolists\Components\Actions\Action::make('refund')
                            ->link()
                            ->hidden(fn ($record) => $record->order_refund_exists)
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

                                if ($record->order_refund_exists) {
                                    Notification::make()
                                        ->title('You have requested your refund')
                                        ->info()
                                        ->send();

                                    return;
                                }

                                if ($record->payment_status == PaymentStatus::Approved) {
                                    OrderRefund::query()
                                        ->create([
                                            'order_id' => $record->getKey(),
                                            'amount' => $record->order_total,
                                        ]);

                                    auth()->user()->notify(new SendUserOrderRefundInReview($record->order_column));

                                    Notification::make()
                                        ->title('Your request to refund this order has been received')
                                        ->success()
                                        ->send();
                                }
                            }),
                    ]),
                Infolists\Components\RepeatableEntry::make('orderDetails')
                    ->columnSpanFull()
                    ->columns(['default' => 2, 'md' => 4])
                    ->schema([
                        Infolists\Components\TextEntry::make('discount.name')
                            ->getStateUsing(fn ($record) => implode(' - ', [
                                $record->discount->brand->name,
                                $record->discount->name,
                            ]))
                            ->url(fn ($record) => route('deals.show', ['id' => $record->discount->slug]))
                            ->label('Name'),
                        Infolists\Components\TextEntry::make('amount')
                            ->getStateUsing(fn ($record) => $record->amount / 100)
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('quantity'),
                        Infolists\Components\TextEntry::make('total')
                            ->getStateUsing(fn ($record) => $record->total / 100)
                            ->money('USD'),
                    ]),
            ]);
    }

    #[Computed]
    public function record()
    {
        return Order::query()
            ->with('orderDetails.discount.brand.media')
            ->withExists(['orderRefund' => fn ($query) => $query->withTrashed()])
            ->firstWhere('uuid', $this->id);
    }
}
