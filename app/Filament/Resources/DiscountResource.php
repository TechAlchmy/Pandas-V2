<?php

namespace App\Filament\Resources;

use App\Enums\DiscountVoucherTypeEnum;
use App\Filament\Resources\DiscountResource\Pages;
use App\Forms\Components\AuditableView;
use App\Models\Category;
use App\Models\Discount;
use App\Models\OfferType;
use Squire\Models\Region;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'Products';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('brand_id')
                    ->live()
                    ->required()
                    ->relationship('brand', 'name', fn ($query) => $query->where('is_active', true))
                    ->searchable(),
                Forms\Components\TextInput::make('name')
                    ->afterStateUpdated(function ($get, $set, ?string $state) {
                        if (! $get('is_slug_changed_manually') && filled($state)) {
                            $set('slug', str($state)->slug());
                        }
                    })
                    ->reactive()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('voucher_type')
                    ->live()
                    ->enum(DiscountVoucherTypeEnum::class)
                    ->options(DiscountVoucherTypeEnum::class)
                    ->default(DiscountVoucherTypeEnum::AddToCart)
                    ->searchable(),
                Forms\Components\TextInput::make('slug')
                    ->afterStateUpdated(function ($set) {
                        $set('is_slug_changed_manually', true);
                    })
                    ->required()
                    ->maxLength(255),
                Forms\Components\Hidden::make('is_slug_changed_manually')
                    ->default(false)
                    ->dehydrated(false),
                Forms\Components\TextInput::make('cta_text')
                    ->maxLength(255),
                Forms\Components\Section::make()
                    ->columns(4)
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->live()
                            ->default(false),
                        Forms\Components\Placeholder::make('views')
                            ->content(fn ($record) => $record->views ?? 0),
                        Forms\Components\Placeholder::make('clicks')
                            ->content(fn ($record) => $record->clicks ?? 0),
                        Forms\Components\Placeholder::make('Orders')
                            ->content(fn ($record) => $record?->loadCount(['orders'])->orders_count),
                    ]),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->required(fn ($get) => $get('is_active'))
                    ->native(false),
                Forms\Components\DateTimePicker::make('ends_at')
                    ->required(fn ($get) => $get('is_active'))
                    ->native(false),
                Forms\Components\TextInput::make('api_link')
                    ->maxLength(255),
                Forms\Components\TextInput::make('link')
                    ->visible(fn ($get) => $get('voucher_type') == DiscountVoucherTypeEnum::GoToSite)
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->visible(fn ($get) => $get('voucher_type') == DiscountVoucherTypeEnum::GetCode)
                    ->maxLength(255),
                Forms\Components\Tabs::make('Heading')
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Amounts')
                            ->schema([
                                Forms\Components\TagsInput::make('amount')
                                    ->formatStateUsing(fn ($state) => \array_map(fn ($amount) => $amount / 100, $state))
                                    ->dehydrateStateUsing(fn ($state) => \array_map(fn ($amount) => $amount * 100, $state))
                                    ->placeholder('Input amounts')
                                    ->splitKeys(['Tab', ' ', ','])
                                    ->tagPrefix('$')
                                    ->nestedRecursiveRules([
                                        'numeric',
                                        'min:1',
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Limit')
                            ->columns()
                            ->schema([
                                Forms\Components\TextInput::make('limit_qty')
                                    ->numeric(),
                                Forms\Components\TextInput::make('limit_amount')
                                    ->formatStateUsing(fn ($state) => $state / 100)
                                    ->dehydrateStateUsing(fn ($state) => $state * 100)
                                    ->prefix('USD')
                                    ->numeric(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Percentage')
                            ->columns()
                            ->schema([
                                Forms\Components\TextInput::make('public_percentage')
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->formatStateUsing(fn ($state) => $state / 100)
                                    ->dehydrateStateUsing(fn ($state) => $state * 100)
                                    ->numeric(),
                                Forms\Components\TextInput::make('percentage')
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->formatStateUsing(fn ($state) => $state / 100)
                                    ->dehydrateStateUsing(fn ($state) => $state * 100)
                                    ->numeric(),
                            ]),
                    ]),
                Forms\Components\Tabs::make('Heading')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Tags')
                            ->schema([
                                Forms\Components\Select::make('tag_id')
                                    ->placeholder('Select Tags')
                                    ->relationship('tags', 'name')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ])
                                    ->multiple()
                                    ->helperText(fn ($state) => count($state) < Tag::query()->count() ? null : 'All selected')
                                    ->hintActions([
                                        Forms\Components\Actions\Action::make('clear')
                                            ->visible(fn ($state) => ! empty($state))
                                            ->action(fn ($component) => $component->state([])),
                                        Forms\Components\Actions\Action::make('all')
                                            ->hidden(fn ($state) => count($state) == Tag::query()->count())
                                            ->action(fn ($component) => $component->state(Tag::query()->pluck('id')->all())),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Types')
                            ->schema([
                                Forms\Components\Select::make('offer_type_id')
                                    ->placeholder('Select Offer Types')
                                    ->relationship('offerTypes', 'type')
                                    ->reactive()
                                    ->multiple()
                                    ->helperText(fn ($state) => count($state) < OfferType::query()->count() ? null : 'All selected')
                                    ->hintActions([
                                        Forms\Components\Actions\Action::make('clear')
                                            ->visible(fn ($state) => ! empty($state))
                                            ->action(fn ($component) => $component->state([])),
                                        Forms\Components\Actions\Action::make('all')
                                            ->hidden(fn ($state) => count($state) == OfferType::query()->count())
                                            ->action(fn ($component) => $component->state(OfferType::query()->pluck('id')->all())),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
                AuditableView::make('audit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand.name'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucherType.type'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->sortable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('brand')
                    ->preload()
                    ->searchable()
                    ->relationship('brand', 'name'),
                Tables\Filters\SelectFilter::make('voucher_type')
                    ->preload()
                    ->searchable()
                    ->relationship('voucherType', 'type'),
                Tables\Filters\SelectFilter::make('offer_type')
                    ->preload()
                    ->searchable()
                    ->relationship('offerTypes', 'type'),
                Tables\Filters\SelectFilter::make('tags')
                    ->preload()
                    ->searchable()
                    ->relationship('tags', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
