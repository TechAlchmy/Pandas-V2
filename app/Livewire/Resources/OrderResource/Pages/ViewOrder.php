<?php

namespace App\Livewire\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Widgets\GiftWidget;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDetailRefund;
use App\Models\OrderRefund;
use App\Models\Setting;
use App\Notifications\SendUserOrderRefundInReview;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Throwable;

use function Filament\Support\format_money;

class ViewOrder extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    public $id;

    public function downloadGiftCard()
    {
        $orderQueue = $this->record->orderQueue;
        $pdf = Pdf::loadView(
            'livewire.gift-card',
            [
                'orderQueue'  => $this->record->orderQueue,
                'size' => 'lg'
            ]
        )
            ->setPaper('a5', 'landscape');

        $fileName = 'card_' . time() . '_' . $orderQueue->id . '.pdf';
        Storage::put('public/temp/' . $fileName, $pdf->stream());
        return Storage::download('public/temp/' . $fileName);
        // This needs to be deleted using a job probably 5 minutes after creating
    }

    public function viewInfolist(Infolist $infolist)
    {
        // dd(Discount::where('code', $this->record->apiCalls[0]->response['contentProviderCode'])->first()?->media?->first()->collection_name);
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

                Infolists\Components\TextEntry::make('note')->hiddenLabel()
                    ->weight(FontWeight::Bold)
                    ->color('warning')
                    ->visible(fn ($record) => OrderStatus::isIncomplete($record->order_status))
                    ->state(Setting::get('order_processing_message'))
                    ->columnSpanFull(),

                ViewEntry::make('apiCalls')
                    ->label('Gift Card Details')
                    ->columnSpanFull()
                    ->view('livewire.gift-card', [
                        'orderQueue'  => $this->record->orderQueue,
                        'size' => 'lg'
                    ])
                    ->visible(!empty($this->record->orderQueue->gifts))
                // ->columns(['default' => 2, 'md' => 4, 'lg' => 5])
                ,

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
                                        ->helperText(fn ($record, $get) => 'Max: ' . $record->orderDetails->firstWhere('id', $get('id'))?->quantity)
                                        ->suffix(function ($record, $get, $state) {
                                            $orderDetail = $record->orderDetails->firstWhere('id', $get('id'));
                                            $orderDetail = $orderDetail->replicate(['quantity']);
                                            $orderDetail->quantity = $state;
                                            return format_money($orderDetail->total / 100, 'USD');
                                        })
                                        ->disabled(fn ($get) => !empty($get('order_detail_refund.id'))),
                                    Forms\Components\TextInput::make('order_detail_refund.note')
                                        ->label('Reason')
                                        ->disabled(fn ($get) => !empty($get('order_detail_refund.id')))
                                        ->maxLength(255),
                                    Forms\Components\Placeholder::make('order_detail_refund.approved_at')
                                        ->label('Status')
                                        ->visible(fn ($get) => !empty($get('order_detail_refund.id')))
                                        ->content(function ($get) use ($record) {
                                            $orderDetail = $record->orderDetails->firstWhere('id', $get('id'));
                                            return $orderDetail->orderDetailRefund->status_message;
                                        }),
                                ]),
                        ])
                        ->action(function ($action, $data, $record) {
                            $refunds = [];
                            foreach ($data['order_details'] as $detail) {
                                $orderDetail = $record->orderDetails->firstWhere('id', $detail['id']);

                                if (!$orderDetail) {
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

                            try {
                                auth()->user()->notify(
                                    new SendUserOrderRefundInReview($this->record->order_column, \array_map(function ($refund) {
                                        return \implode(' - ', [
                                            $refund->orderDetail->discount->brand->name,
                                            $refund->orderDetail->discount->name,
                                            'x' . $refund->quantity,
                                            'Reason:' . $refund->note,
                                        ]);
                                    }, $refunds)),
                                );
                            } catch (\Throwable $e) {
                                logger()->error($e->getMessage());
                            }


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
            })->with('apiCalls', fn ($query) => $query->where('success', true))
            ->withExists(['orderDetailRefunds' => fn ($query) => $query->withTrashed()])
            ->firstWhere('uuid', $this->id);
    }
}
