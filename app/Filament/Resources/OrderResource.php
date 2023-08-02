<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers\OrderDetailsRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'E-Commerce';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Section::make('Order Data')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name'),

                        Forms\Components\TextInput::make('order_status')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('order_number')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('order_total')
                            ->numeric()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('order_subtotal')
                            ->numeric()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('order_discount')
                            ->numeric()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('order_tax')
                            ->numeric()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('payment_method')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('payment_status')
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('order_date'),
                    ]),

                Section::make('Associations')
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Select::make('created_by')
                    ->label('Created by')
                    ->disabled()
                    ->relationship('creator', 'name'),

                        Forms\Components\Select::make('updated_by')
                            ->label('Update by')
                            ->disabled()
                            ->relationship('updater', 'name'),

                        Forms\Components\Select::make('deleted_by')
                            ->label('Deleted by')
                            ->disabled()
                            ->relationship('deleter', 'name'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->url(fn (Order $record) => route('filament.resources.users.edit', $record->user->id)),

                Tables\Columns\BadgeColumn::make('order_status')
                    ->colors([
                        'secondary' => 'pending',
                        'secondary' => 'processing',
                        'primary' => 'on hold',
                        'warning' => 'refunded',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'danger' => 'failed',
                    ]),

                Tables\Columns\TextColumn::make('order_number')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('order_total'),

                Tables\Columns\TextColumn::make('order_subtotal')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('order_discount')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('order_tax')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'primary',
                        'secondary' => 'pending',
                        'warning' => 'refunded',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ])
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_by')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('updated_by')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('deleted_by')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('order_date')
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                TernaryFilter::make('trashed')
                    ->placeholder('Without trashed records')
                    ->trueLabel('With trashed records')
                    ->falseLabel('Only trashed records')
                    ->queries(
                        true: fn (Builder $query) => $query->withTrashed(),
                        false: fn (Builder $query) => $query->onlyTrashed(),
                        blank: fn (Builder $query) => $query->withoutTrashed(),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getRelations(): array
    {
        return [
            OrderDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
