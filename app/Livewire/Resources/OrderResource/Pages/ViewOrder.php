<?php

namespace App\Livewire\Resources\OrderResource\Pages;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderDetailRefund;
use App\Models\OrderRefund;
use App\Notifications\SendUserOrderRefundInReview;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Livewire\Attributes\Computed;
use Livewire\Component;

use function Filament\Support\format_money;

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
                    ->money('USD'),
                Infolists\Components\RepeatableEntry::make('orderDetails')
                    ->columnSpanFull()
                    ->columns(['default' => 2, 'md' => 4, 'lg' => 5])
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
                        Infolists\Components\Actions::make([
                            Infolists\Components\Actions\Action::make('redeem')
                                ->hidden(fn ($record) => $record->orderDetailRefund)
                                ->modalHeading(fn ($record) => \implode(' - ', [
                                    'Request refund for ' . $record->discount->brand->name,
                                    $record->discount->name,
                                ]))
                                ->fillForm(fn ($record) => [
                                    'quantity' => $record->quantity,
                                ])
                                ->form(fn ($record) => [
                                    Forms\Components\Grid::make()
                                        ->schema([
                                            Forms\Components\TextInput::make('quantity')
                                                ->numeric()
                                                ->required()
                                                ->maxValue($record->quantity)
                                                ->minValue(1),
                                            Forms\Components\TextInput::make('note')
                                                ->label('Reason to refund')
                                                ->maxLength(255),
                                        ]),
                                ])
                                ->successNotificationTitle('Successfully Requested to refund the item')
                                ->successNotification(function ($notification, $record) {
                                    auth()->user()->notify(new SendUserOrderRefundInReview(
                                        $record->order->order_column,
                                        \implode(' - ', [
                                            $record->discount->brand->name,
                                            $record->discount->name,
                                        ]),
                                    ));
                                    return $notification;
                                })
                                ->action(function ($action, $data, $record) {
                                    OrderDetailRefund::query()
                                        ->updateOrCreate([
                                            'order_detail_id' => $record->getKey(),
                                        ], [
                                            'quantity' => $data['quantity'],
                                            'note' => $data['note'],
                                        ]);

                                    $action->success();
                                }),
                        ]),
                    ]),
            ]);
    }

    #[Computed]
    public function record()
    {
        return Order::query()
            ->with('orderDetails.discount.brand.media')
            ->with('orderDetails', function ($query) {
                $query->with('orderDetailRefund', fn ($query) => $query->withTrashed())
                    ->with('discount.brand.media');
            })
            // ->withExists(['orderRefund' => fn ($query) => $query->withTrashed()])
            ->firstWhere('uuid', $this->id);
    }
}
