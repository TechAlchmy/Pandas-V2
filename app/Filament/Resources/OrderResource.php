<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers\OrderDetailsRelationManager;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'E-Commerce';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order Data')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name'),

                        Forms\Components\TextInput::make('order_status'),

                        Forms\Components\TextInput::make('order_number')
                            ->disabled(fn (string $context): bool => $context !== 'create')
                            ->required(),

                        Forms\Components\TextInput::make('order_total')
                            ->disabled(fn (string $context): bool => $context !== 'create')
                            ->numeric(),

                        Forms\Components\TextInput::make('order_subtotal')
                            ->disabled(fn (string $context): bool => $context !== 'create')
                            ->numeric(),

                        Forms\Components\TextInput::make('order_discount')
                            ->disabled(fn (string $context): bool => $context !== 'create')
                            ->numeric(),

                        Forms\Components\TextInput::make('order_tax')
                            ->disabled(fn (string $context): bool => $context !== 'create')
                            ->numeric(),

                        Forms\Components\TextInput::make('payment_method')->disabled(),

                        Forms\Components\TextInput::make('payment_status')->disabled(),

                        Forms\Components\DatePicker::make('order_date')->disabled()->default(now()),
                    ]),

                Tabs::make('Heading')
                    ->tabs([
                        Tabs\Tab::make('Edited By')
                            ->schema([
                                Placeholder::make('edited_by')->content(function ($record) {
                                    return $record && $record->updatedBy ? $record->updatedBy->name : null;
                                }),
                                Placeholder::make('Email')->content(function ($record) {
                                    return $record && $record->updatedBy ? $record->updatedBy->email : null;
                                }),
                                Placeholder::make('Last updated')->content(function ($record) {
                                    return $record && $record->updated_at ? $record->updated_at->format('m/d/Y h:i:s A') : null;
                                }),

                            ])->columns(3),
                        Tabs\Tab::make('Created By')
                            ->schema([
                                Placeholder::make('created_by')->content(function ($record) {
                                    return $record && $record->createdBy ? $record->createdBy->name : null;
                                }),
                                Placeholder::make('email')->content(function ($record) {
                                    return $record && $record->createdBy ? $record->createdBy->email : null;
                                }),
                                Placeholder::make('created_at')->content(function ($record) {
                                    return $record && $record->created_at ? $record->created_at->format('m/d/Y h:i:s A') : null;
                                }),
                            ])->columns(3),
                        Tabs\Tab::make('Deleted By')
                            ->schema([
                                Placeholder::make('deledted_by')->content(function ($record) {
                                    return $record && $record->deletedBy ? $record->deletedBy->name : null;
                                }),
                                Placeholder::make('email')->content(function ($record) {
                                    return $record && $record->deletedBy ? $record->deletedBy->email : null;
                                }),
                                Placeholder::make('created_at')->content(function ($record) {
                                    return $record && $record->deelted_at ? $record->deelted_at->format('m/d/Y h:i:s A') : null;
                                }),
                            ])->columns(3),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable(),

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
                Action::make('refund')->button(),
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
