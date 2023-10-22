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
use App\Models\VoucherType;
use Closure;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'Products';

    protected static ?int $navigationSort = 1;

    // public static function getNavigationLabel(): string
    // {
    //     $count = static::$model::where('is_approved', false)->count();
    //     return 'Discounts' . ($count ? " ({$count})" : '');
    // }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::flagged()->count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('brand_id')
                    ->live()
                    ->required()
                    ->relationship('brand', 'name', fn ($query) => $query->where('is_active', true))
                    ->searchable()
                    ->disabled(fn (string $context, ?Model $record) => $context === 'edit' && $record?->is_bhn),

                Forms\Components\SpatieMediaLibraryFileUpload::make('featured')
                    ->collection('featured')
                    ->disk('s3')
                    ->openable()
                    ->downloadable(),

                Forms\Components\TextInput::make('name')->live(debounce: 1000)
                    ->afterStateUpdated(function ($get, $set, ?string $state) {
                        if (!$get('is_slug_changed_manually') && filled($state)) {
                            $set('slug', str($state)->slug());
                        }
                    })
                    ->reactive()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('voucher_type')
                    ->required()
                    ->live()
                    ->enum(DiscountVoucherTypeEnum::class)
                    ->options(DiscountVoucherTypeEnum::collect()
                        ->mapWithKeys(fn ($type) => [
                            $type->value => $type->getLabel(),
                        ]))
                    ->disableOptionWhen(function ($value) {
                        return in_array($value, [
                            DiscountVoucherTypeEnum::ExternalApiLink->value,
                            DiscountVoucherTypeEnum::GeneratedDiscountCode->value,
                        ]);
                    })
                    ->default(DiscountVoucherTypeEnum::DefinedAmountsGiftCard->value)
                    ->searchable()
                    ->disabled(fn (string $context, ?Model $record) => $context === 'edit' && $record?->is_bhn),

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
                    ->required()
                    ->maxLength(255)
                    ->visible(fn ($get) => \filled($get('voucher_type')))
                    ->datalist([
                        'Add To Cart',
                        'Get Code',
                        'Redeem Now',
                        'Go To Link',
                    ]),

                Forms\Components\Section::make()
                    ->columns(4)
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->live()
                            ->required()
                            ->default(false),
                        Forms\Components\Placeholder::make('views')
                            ->content(fn ($record) => $record->views ?? 0),
                        Forms\Components\Placeholder::make('clicks')
                            ->content(fn ($record) => $record->clicks ?? 0),
                        Forms\Components\Placeholder::make('Orders')
                            ->content(fn ($record) => $record?->loadCount(['orders'])->orders_count),
                    ]),

                Section::make()->schema([
                    Forms\Components\Toggle::make('timed')
                        ->label('Timed?')
                        ->live()
                        ->columnSpan(2),

                    Forms\Components\DateTimePicker::make('starts_at')
                        ->label('Starts')
                        ->required(fn (Get $get) => (bool) $get('timed'))
                        ->native(false)
                        ->visible(fn ($get) => (bool) $get('timed'))
                        ->default(now()->format('Y-m-d'))
                        ->columnSpan(3),

                    Forms\Components\DateTimePicker::make('ends_at')
                        ->label('Ends')
                        ->required(fn (Get $get) => (bool) $get('timed'))
                        ->native(false)
                        ->visible(fn ($get) => (bool) $get('timed'))
                        ->default(now()->format('Y-m-d'))
                        ->columnSpan(3)

                ])->columnSpan(1)->columns(8),

                Forms\Components\TextInput::make('api_link')
                    ->visible(fn ($get) => \in_array($get('voucher_type'), [
                        DiscountVoucherTypeEnum::ExternalApiLink->value,
                        DiscountVoucherTypeEnum::GeneratedDiscountCode->value,
                    ]))
                    ->required(fn ($get) => \in_array($get('voucher_type'), [
                        DiscountVoucherTypeEnum::ExternalApiLink->value,
                        DiscountVoucherTypeEnum::GeneratedDiscountCode->value,
                    ]))
                    ->maxLength(255),

                Forms\Components\TextInput::make('link')
                    ->visible(fn ($get) => $get('voucher_type') == DiscountVoucherTypeEnum::ExternalLink->value)
                    ->required(fn ($get) => $get('voucher_type') == DiscountVoucherTypeEnum::ExternalLink->value)
                    ->maxLength(255),

                Forms\Components\TextInput::make('code')
                    ->visible(fn ($get) => $get('voucher_type') == DiscountVoucherTypeEnum::FixedDiscountCode->value)
                    ->required(fn ($get) => $get('voucher_type') == DiscountVoucherTypeEnum::FixedDiscountCode->value)
                    ->maxLength(255),

                Forms\Components\Tabs::make('Heading')
                    ->columnSpanFull()
                    ->visible(fn ($get) => \in_array($get('voucher_type'), [
                        DiscountVoucherTypeEnum::DefinedAmountsGiftCard->value,
                    ]))
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Amounts')
                            ->schema([
                                Forms\Components\TagsInput::make('amount')
                                    ->formatStateUsing(fn ($state) => array_map(fn ($amount) => $amount / 100, $state))
                                    ->dehydrateStateUsing(fn ($state) => array_map(fn ($amount) => $amount * 100, $state))
                                    ->placeholder('Input amounts')
                                    ->splitKeys(['Tab', ' ', ','])
                                    ->tagPrefix('$')
                                    ->nestedRecursiveRules([
                                        'numeric',
                                        'min:1'
                                    ])->hint(fn ($context, $livewire) => $context === 'edit' && $livewire->record->is_bhn ? "Min: {$livewire->record->bh_min} | Max: {$livewire->record->bh_max}" : null),
                            ]),
                        Forms\Components\Tabs\Tab::make('Limit')
                            ->columns()
                            ->schema([
                                Forms\Components\TextInput::make('limit_qty')
                                    ->visible(fn ($get) => \in_array(DiscountVoucherTypeEnum::tryFrom($get('voucher_type')), [
                                        DiscountVoucherTypeEnum::DefinedAmountsGiftCard,
                                    ]))
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
                                    ->formatStateUsing(fn ($state) => filled($state) ? $state / 100 : null)
                                    ->dehydrateStateUsing(fn ($state) => filled($state) ? $state * 100 : null)
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
                    ->columnSpanFull()
                    ->visible(fn ($get) => \in_array(DiscountVoucherTypeEnum::tryFrom($get('voucher_type')), [
                        DiscountVoucherTypeEnum::TopUpGiftCard,
                    ]))
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Limit')
                            ->columns()
                            ->schema([
                                Forms\Components\TextInput::make('limit_amount')
                                    ->formatStateUsing(fn ($state) => filled($state) ? $state / 100 : null)
                                    ->dehydrateStateUsing(fn ($state) => filled($state) ? $state * 100 : null)
                                    ->prefix('USD')
                                    ->numeric()
                                    ->hidden(fn (Get $get) => $get('voucher_type') === DiscountVoucherTypeEnum::TopUpGiftCard->value),

                                Forms\Components\TextInput::make('bh_min')->label('Min Amount')
                                    ->formatStateUsing(fn ($state) => filled($state) ? $state / 100 : null)
                                    ->dehydrateStateUsing(fn ($state) => filled($state) ? $state * 100 : null)
                                    ->prefix('USD')
                                    ->numeric()
                                    ->readOnly(fn ($livewire) => $livewire->record?->is_bhn)
                                    ->visible(fn (Get $get) => $get('voucher_type') === DiscountVoucherTypeEnum::TopUpGiftCard->value),

                                Forms\Components\TextInput::make('bh_max')->label('Max Amount')
                                    ->formatStateUsing(fn ($state) => filled($state) ? $state / 100 : null)
                                    ->dehydrateStateUsing(fn ($state) => filled($state) ? $state * 100 : null)
                                    ->prefix('USD')
                                    ->numeric()
                                    ->readOnly(fn ($livewire) => $livewire->record?->is_bhn)
                                    ->visible(fn (Get $get) => $get('voucher_type') === DiscountVoucherTypeEnum::TopUpGiftCard->value),
                            ]),
                        Forms\Components\Tabs\Tab::make('Percentage')
                            ->columns()
                            ->schema([
                                Forms\Components\TextInput::make('public_percentage')
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->formatStateUsing(fn ($state) => filled($state) ? $state / 100 : null)
                                    ->dehydrateStateUsing(fn ($state) => filled($state) ? $state * 100 : null)
                                    ->numeric(),
                                Forms\Components\TextInput::make('percentage')
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->formatStateUsing(fn ($state) => filled($state) ? $state / 100 : null)
                                    ->dehydrateStateUsing(fn ($state) => filled($state) ? $state * 100 : null)
                                    ->numeric(),
                            ]),
                    ]),
                Forms\Components\RichEditor::make('excerpt')
                    ->placeholder('Enter Description')
                    ->columnSpanFull(),
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
                                            ->visible(fn ($state) => !empty($state))
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
                                            ->visible(fn ($state) => !empty($state))
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

                SpatieMediaLibraryImageColumn::make('featured')
                    ->collection('featured'),

                Tables\Columns\TextColumn::make('voucher_type')
                    ->formatStateUsing(fn ($state) => $state->getLabel()),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('is_approved')
                    ->label('Approved')
                    ->badge(fn ($record) => $record->is_bhn)
                    ->state(fn ($record) => $record->is_bhn ? ($record->is_approved ? 'Yes' : 'No') : null)
                    ->color(fn ($state) => match ($state) {
                        'Yes' => 'success',
                        'No' => 'danger'
                    })->action(\Filament\Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->modalHeading(fn ($record) => $record->is_approved ? "Unapprove {$record->name} ?" : "Approve {$record->name} ?")
                        ->action(fn ($record) => $record->update(['is_approved' => !$record->is_approved]))),

                Tables\Columns\TextColumn::make('starts_at')
                    ->sortable()
                    ->dateTime('Y-m-d'),

                Tables\Columns\TextColumn::make('ends_at')
                    ->sortable()
                    ->dateTime('Y-m-d'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('brand')
                    ->preload()
                    ->searchable()
                    ->relationship('brand', 'name'),

                Tables\Filters\SelectFilter::make('voucher_type')
                    ->native(false)
                    ->options(DiscountVoucherTypeEnum::collect()
                        ->mapWithKeys(fn ($type) => [
                            $type->value => $type->getLabel(),
                        ])),

                Tables\Filters\SelectFilter::make('offer_type')
                    ->preload()
                    ->searchable()
                    ->relationship('offerTypes', 'type'),

                Tables\Filters\SelectFilter::make('tags')
                    ->preload()
                    ->searchable()
                    ->relationship('tags', 'name'),

                TernaryFilter::make('is_approved')->label('Needs Attention')
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_approved', false),
                        false: fn (Builder $query) => $query->where('is_approved', true),
                        blank: fn (Builder $query) => $query
                    ),
            ])
            ->recordClasses(fn (Model $record) => match ($record->is_approved) {
                false => 'bg-gray-100',
                default => null,
            })
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('web')
                    ->icon('heroicon-o-link')
                    ->url(fn ($record) => url("deals/$record->slug"))
                    ->openUrlInNewTab(),
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
