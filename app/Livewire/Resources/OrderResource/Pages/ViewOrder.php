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
            ->columns()
            ->schema([
                Infolists\Components\TextEntry::make('order_total')
                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                    ->getStateUsing(fn ($record) => $record->order_total / 100)
                    ->money('USD'),
                Infolists\Components\RepeatableEntry::make('orderDetails')
                    ->columnSpanFull()
                    ->columns(['default' => 2, 'md' => 4])
                    ->schema([
                        Infolists\Components\TextEntry::make('discount.brand.name')
                            ->url(fn ($record) => route('deals.index', ['filter' => ['brand_id' => $record->discount->brand_id]]))
                            ->label('Brand'),
                        Infolists\Components\TextEntry::make('discount.name')
                            ->url(fn ($record) => route('deals.show', ['id' => $record->discount->slug]))
                            ->label('Name'),
                        Infolists\Components\TextEntry::make('amount')
                            ->getStateUsing(fn ($record) => $record->amount / 100)
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('quantity'),
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
