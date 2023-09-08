<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers\OrderDetailsRelationManager;
use App\Forms\Components\AuditableView;
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
                    ->disabled(fn (string $context): bool => $context !== 'create')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name'),

                        Forms\Components\Select::make('order_status')->options(OrderStatus::options()),

                        Forms\Components\TextInput::make('order_column')
                            ->label('Order Number')
                            ->dehydrated(fn (string $context): bool => $context !== 'create')
                            ->required(),

                        Forms\Components\TextInput::make('order_total')
                            ->dehydrated(fn (string $context): bool => $context !== 'create')
                            ->numeric(),

                        Forms\Components\TextInput::make('order_subtotal')
                            ->dehydrated(fn (string $context): bool => $context !== 'create')
                            ->numeric(),

                        Forms\Components\TextInput::make('order_discount')
                            ->dehydrated(fn (string $context): bool => $context !== 'create')
                            ->numeric(),

                        Forms\Components\TextInput::make('order_tax')
                            ->dehydrated(fn (string $context): bool => $context !== 'create')
                            ->numeric(),

                        Forms\Components\TextInput::make('payment_method')->dehydrated(false),

                        Forms\Components\Select::make('payment_status')->options(PaymentStatus::options())->dehydrated(false),

                        Forms\Components\DatePicker::make('order_date')->dehydrated(false)->default(now()),
                    ]),

                AuditableView::make(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ...[
                    Tables\Columns\TextColumn::make('user.name')
                        ->searchable()
                        ->url(fn (Order $record) => route('filament.admin.resources.users.edit', $record->user->id)),
                ],
                ...static::getTableColumns(),
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

    public static function getTableColumns(): array
    {
        return [
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
                ->formatStateUsing(fn ($record) => $record->money_order_total),

            Tables\Columns\TextColumn::make('order_subtotal')
                ->formatStateUsing(fn ($record) => $record->money_order_subtotal)
                ->toggleable()
                ->toggledHiddenByDefault(),

            Tables\Columns\TextColumn::make('order_discount')
                ->formatStateUsing(fn ($record) => $record->money_order_discount)
                ->toggleable()
                ->toggledHiddenByDefault(),

            Tables\Columns\TextColumn::make('order_tax')
                ->formatStateUsing(fn ($record) => $record->money_order_tax)
                ->toggleable()
                ->toggledHiddenByDefault(),

            Tables\Columns\TextColumn::make('payment_method')
                ->toggleable()
                ->toggledHiddenByDefault(),

            Tables\Columns\TextColumn::make('payment_status')
                ->badge()
                ->colors([
                    'primary',
                    'secondary' => 'pending',
                    'warning' => 'refunded',
                    'success' => 'paid',
                    'danger' => 'failed',
                ])
                ->toggleable(),

            Tables\Columns\TextColumn::make('order_date')
                ->toggleable()
                ->toggledHiddenByDefault(),
        ];
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
