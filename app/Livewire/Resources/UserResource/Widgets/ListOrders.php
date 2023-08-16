<?php

namespace App\Livewire\Resources\UserResource\Widgets;

use App\Models\Order;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use Filament\Tables\Table;
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_subtotal'),
                Tables\Columns\TextColumn::make('order_discount'),
                Tables\Columns\TextColumn::make('order_tax'),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->colors([
                        'primary',
                        'secondary' => 'pending',
                        'warning' => 'refunded',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ]),
            ]);
    }
}
