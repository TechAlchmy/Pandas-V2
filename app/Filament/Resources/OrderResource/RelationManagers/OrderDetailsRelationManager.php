<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Resources\DiscountResource;
use Filament\Forms;
use Filament\Tables;
use App\Models\OrderDetail;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;

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
            ->columns([
                Tables\Columns\TextColumn::make('discount.name')
                    ->url(fn (OrderDetail $record) => DiscountResource::getUrl('edit', ['record' => $record->discount]))
                    ->searchable()
                    ->label('Name'),

                Tables\Columns\TextColumn::make('quantity')->label('Qauntity'),

                Tables\Columns\TextColumn::make('amount')->label('Item Price'),

                Tables\Columns\TextColumn::make('total'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('refund')->button(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
