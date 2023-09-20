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
use Illuminate\Support\Carbon;
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
                    ->label('Details')
                    ->columnSpanFull()
                    ->columns(['default' => 2, 'md' => 4, 'lg' => 5])
                    ->hintAction(Infolists\Components\Actions\Action::make('refund')
                        ->fillForm(fn ($record) => [
                            'order_details' => $record->orderDetails,
                        ])
                        ->form(fn ($record) => [
                            Forms\Components\Repeater::make('order_details')
                                ->columns(3)
                                ->reorderable(false)
                                ->addable(false)
                                ->maxItems($record->orderDetails->count())
                                ->minItems(1)
                                ->itemLabel(fn ($state) => \implode(' - ', [
                                    \data_get($state, 'discount.brand.name'),
                                    \data_get($state, 'discount.name'),
                                ]))
                                ->schema([
                                    Forms\Components\TextInput::make('order_detail_refund.quantity')
                                        ->live()
                                        ->formatStateUsing(fn ($state, $get) => $state ?? $get('quantity'))
                                        ->numeric()
                                        ->required()
                                        ->minValue(1)
                                        ->maxValue(fn ($record, $get) => $record->orderDetails->firstWhere('id', $get('id'))?->quantity)
                                        ->helperText(fn ($record, $get) => 'Max: '.$record->orderDetails->firstWhere('id', $get('id'))?->quantity)
                                        ->suffix(function ($record, $get, $state) {
                                            $orderDetail = $record->orderDetails->firstWhere('id', $get('id'));
                                            $orderDetail = $orderDetail->replicate(['quantity']);
                                            $orderDetail->quantity = $state;
                                            return format_money($orderDetail->total / 100, 'USD');
                                        })
                                        ->disabled(fn ($get) => ! empty($get('order_detail_refund.id'))),
                                    Forms\Components\TextInput::make('order_detail_refund.note')
                                        ->label('Reason')
                                        ->disabled(fn ($get) => ! empty($get('order_detail_refund.id')))
                                        ->maxLength(255),
                                    Forms\Components\Placeholder::make('order_detail_refund.approved_at')
                                        ->label('Status')
                                        ->visible(fn ($get) => ! empty($get('order_detail_refund.id')))
                                        ->content(function ($state, $get) {
                                            if (! empty($state)) {
                                                return 'Approved at ' . Carbon::parse($state)?->format('d M Y');
                                            }

                                            if ($get('order_detail_refund.deleted_at')) {
                                                return 'Rejected at ' . Carbon::parse($get('order_detail_refund.deleted_at'))?->format('d M Y');
                                            }

                                            return 'In Review';
                                        }),
                                ]),
                        ])
                        ->action(function ($action, $data, $record) {
                            $refunds = [];
                            foreach ($data['order_details'] as $detail) {
                                $orderDetail = $record->orderDetails->firstWhere('id', $detail['id']);

                                if (! $orderDetail) {
                                    continue;
                                }

                                if ($orderDetail->orderDetailRefund?->approved_at) {
                                    continue;
                                }

                                $refunds[] = OrderDetailRefund::query()
                                    ->updateOrCreate([
                                        'order_detail_id' => $detail['id'],
                                    ], [
                                        'quantity' => \data_get($detail, 'order_detail_refund.quantity'),
                                        'note' => \data_get($detail, 'order_detail_refund.note'),
                                    ]);
                            }

                            auth()->user()->notify(
                                new SendUserOrderRefundInReview($this->record->order_column, \array_map(function ($refund) {
                                    return \implode(' - ', [
                                        $refund->orderDetail->discount->brand->name,
                                        $refund->orderDetail->discount->name,
                                        'x'.$refund->quantity,
                                        'Reason:'.$refund->note,
                                    ]);
                                }, $refunds)),
                            );

                            $action->success();
                        })
                        ->successNotificationTitle('Successfully requested to refund item'))
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
                        // Infolists\Components\Actions::make([
                        //     Infolists\Components\Actions\Action::make('request_refund')
                        //         ->link()
                        //         ->hidden(fn ($record) => $record->orderDetailRefund)
                        //         ->modalHeading(fn ($record) => \implode(' - ', [
                        //             'Request refund for ' . $record->discount->brand->name,
                        //             $record->discount->name,
                        //         ]))
                        //         ->modalSubmitActionLabel('Request Refund')
                        //         ->fillForm(fn ($record) => [
                        //             'quantity' => $record->quantity,
                        //         ])
                        //         ->form(fn ($record) => [
                        //             Forms\Components\Grid::make()
                        //                 ->schema([
                        //                     Forms\Components\TextInput::make('quantity')
                        //                         ->live()
                        //                         ->numeric()
                        //                         ->required()
                        //                         ->helperText('Max: ' . $record->quantity)
                        //                         ->maxValue($record->quantity)
                        //                         ->minValue(1)
                        //                         ->suffix(function ($state) use ($record) {
                        //                             $record->quantity = $state;
                        //                             return format_money($record->total / 100, 'USD');
                        //                         }),
                        //                     Forms\Components\TextInput::make('note')
                        //                         ->label('Reason to refund')
                        //                         ->maxLength(255),
                        //                 ]),
                        //         ])
                        //         ->successNotificationTitle('Successfully Requested to refund the item')
                        //         ->successNotification(function ($notification, $record) {
                        //             auth()->user()->notify(new SendUserOrderRefundInReview(
                        //                 $record->order->order_column,
                        //                 \implode(' - ', [
                        //                     $record->discount->brand->name,
                        //                     $record->discount->name,
                        //                 ]),
                        //             ));
                        //             return $notification;
                        //         })
                        //         ->action(function ($action, $data, $record) {
                        //             OrderDetailRefund::query()
                        //                 ->updateOrCreate([
                        //                     'order_detail_id' => $record->getKey(),
                        //                 ], [
                        //                     'quantity' => $data['quantity'],
                        //                     'note' => $data['note'],
                        //                 ]);

                        //             $action->success();
                        //         }),
                        // ]),
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
            ->withExists(['orderDetailRefunds' => fn ($query) => $query->withTrashed()])
            ->firstWhere('uuid', $this->id);
    }
}
