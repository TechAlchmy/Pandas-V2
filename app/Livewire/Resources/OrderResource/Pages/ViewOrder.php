<?php

namespace App\Livewire\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists;
use Filament\Infolists\Infolist;
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
                    ->money('USD'),
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
            ->firstWhere('uuid', $this->id);
    }
}
