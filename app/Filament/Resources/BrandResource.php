<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Forms\Components\AuditableView;
use App\Models\Brand;
use App\Models\Category;
use Squire\Models\Region;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Ternary;
use Ramsey\Uuid\Uuid;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationGroup = 'Branding';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::$model::needsAttention()->count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->live()
                    ->placeholder('Enter Brand Name')
                    ->maxLength(255)
                    ->afterStateUpdated(function ($get, $set, ?string $state) {
                        if (!$get('is_slug_changed_manually') && filled($state)) {
                            $set('slug', str($state)->slug());
                        }
                    }),
                Forms\Components\TextInput::make('slug')
                    ->afterStateUpdated(function ($set) {
                        $set('is_slug_changed_manually', true);
                    })
                    ->unique(Brand::class, 'slug', ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\Hidden::make('is_slug_changed_manually')
                    ->default(false)
                    ->dehydrated(false),
                TextInput::make('link')
                    ->required()
                    ->placeholder('Enter Brand Link'),
                RichEditor::make('description')
                    ->placeholder('Enter Brand Description')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Section::make('Brand Details')
                    ->columns(3)
                    ->columnSpan(1)
                    ->schema([
                        Toggle::make('is_active')
                            ->default(false),
                        Placeholder::make('views')
                            ->content(fn ($record) => $record->views ?? 0),
                        Placeholder::make('Products'),
                    ]),

                Forms\Components\Section::make()
                    ->columns(1)
                    ->columnSpan(1)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('Logo')
                            ->collection('logo')
                            ->downloadable()
                            ->openable()
                            ->image(),
                    ]),

                Tabs::make('Heading')
                    ->tabs([
                        Tabs\Tab::make('Brand Catregories')
                            ->schema([
                                Select::make('category_id')
                                    ->placeholder('Select Category')
                                    ->relationship('categories', 'name')
                                    ->required()
                                    ->reactive()
                                    ->multiple()
                                    ->helperText(fn ($state) => count($state) < Category::query()->count() ? null : 'All selected')
                                    ->hintActions([
                                        Forms\Components\Actions\Action::make('clear')
                                            ->visible(fn ($state) => !empty($state))
                                            ->action(fn ($component) => $component->state([])),
                                        Forms\Components\Actions\Action::make('all')
                                            ->hidden(fn ($state) => count($state) == Category::query()->count())
                                            ->action(fn ($component) => $component->state(Category::query()->pluck('id')->all())),
                                    ]),
                            ]),
                        Tabs\Tab::make('Regions')
                            ->schema([
                                Forms\Components\Select::make('region_ids')
                                    ->live()
                                    ->required()
                                    ->default(Region::query()->where('country_id', 'us')->pluck('id')->all())
                                    ->placeholder('Select Regions')
                                    ->multiple()
                                    ->getOptionLabelsUsing(function ($values) {
                                        return Region::query()->find($values)->pluck('name');
                                    })
                                    ->helperText(fn ($state) => count($state) < Region::query()->where('country_id', 'us')->count() ? null : 'All selected')
                                    ->hintActions([
                                        Forms\Components\Actions\Action::make('clear')
                                            ->visible(fn ($state) => !empty($state))
                                            ->action(fn ($component) => $component->state([])),
                                        Forms\Components\Actions\Action::make('all')
                                            ->hidden(fn ($state) => count($state) == Region::query()->where('country_id', 'us')->count())
                                            ->action(fn ($component) => $component->state(Region::query()->where('country_id', 'us')->pluck('id')->all())),
                                    ]),

                            ]),
                    ])->columnSpanFull(),

                AuditableView::make('Audit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logo')
                    ->extraImgAttributes(['title' => 'Company logo']),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->limit(30),
                TextColumn::make('views'),
                ToggleColumn::make('is_active'),

                TextColumn::make('link')
                    ->badge()
                    ->limit(10)
                    ->copyable()
                    ->copyMessage('Link copied')
                    ->copyMessageDuration(1500),

            ])->defaultSort('name')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TrashedFilter::make(),

                //name search filter
                Filter::make('Search')
                    ->form([
                        TextInput::make('search')
                            ->placeholder('Search Brands'),

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['search'],
                                fn (Builder $query, $name): Builder => $query->where('name', 'like', "%{$name}%")
                            );
                    }),
                TernaryFilter::make('needs_attention')
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->queries(
                        true: fn (Builder $query) => $query->needsAttention(),
                        false: fn (Builder $query) => !$query->doesntNeedAttention(),
                        blank: fn (Builder $query) => $query
                    ),

            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('refresh')
                    ->action(function ($livewire) {
                        $livewire->js('$wire.$refresh()');
                    }),
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
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
