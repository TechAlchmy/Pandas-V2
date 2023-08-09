<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Forms\Components\AuditableView;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Region;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Branding';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->reactive()
                    ->placeholder('Enter Brand Name')
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->disabled()
                    ->required()
                    ->unique(Brand::class, 'slug', fn ($record) => $record),
                TextInput::make('link')
                    ->required()
                    ->placeholder('Enter Brand Link'),
                TextInput::make('uniqid')
                    ->required()
                    ->default(function () {
                        return Uuid::uuid4()->toString();
                    })
                    ->disabled(),
                MarkdownEditor::make('description')
                    ->placeholder('Enter Brand Description')
                    ->required()
                    ->columnSpan(2),

                Forms\Components\Card::make()
                    ->columns(3)
                    ->columnSpan(1)
                    ->schema([
                        Toggle::make('is_active')
                            ->default(false)
                            ->onColor('success')
                            ->offColor('danger'),
                        Placeholder::make('views')
                            ->content(fn ($record) => $record->views ?? 0),
                        Placeholder::make('Products'),
                    ]),

                FileUpload::make('logo')
                    ->disk('public')
                    ->directory('brandsimages')
                    ->image(),

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
                                    ->helperText(fn ($get) => count($get('category_id')) < Category::query()->count() ? null : 'All selected')
                                    ->hintActions([
                                        Forms\Components\Actions\Action::make('clear')
                                            ->visible(fn ($get) => ! empty($get('category_id')))
                                            ->action(function ($set) {
                                                $set('category_id', []);
                                            }),
                                        Forms\Components\Actions\Action::make('all')
                                            ->hidden(fn ($get) => count($get('category_id')) == Category::query()->count())
                                            ->action(function ($set) {
                                                $set('category_id', Category::query()->pluck('id')->all());
                                            }),
                                    ]),
                            ]),
                        Tabs\Tab::make('Regions')
                            ->schema([
                                Select::make('region_id')
                                    ->default(Region::query()->pluck('id')->all())
                                    ->placeholder('Select Brand Regions')
                                    ->relationship('regions', 'name')
                                    ->reactive()
                                    ->multiple()
                                    ->helperText(fn ($get) => count($get('region_id')) < Region::query()->count() ? null : 'All selected')
                                    ->hintActions([
                                        Forms\Components\Actions\Action::make('clear')
                                            ->visible(fn ($get) => ! empty($get('region_id')))
                                            ->action(function ($set) {
                                                $set('region_id', []);
                                            }),
                                        Forms\Components\Actions\Action::make('all')
                                            ->hidden(fn ($get) => count($get('region_id')) == Region::query()->count())
                                            ->action(function ($set) {
                                                $set('region_id', Region::query()->pluck('id')->all());
                                            }),
                                    ]),

                            ]),
                        Tabs\Tab::make('Organizations')
                            ->schema([
                                Select::make('Brand Organizations')
                                    ->placeholder('Select Brand Organizations')
                                    ->relationship('organizations', 'name')
                                    ->reactive()
                                    ->helperText('Leave blank to select all organizations')
                                    ->multiple(),
                            ]),
                    ])->columnSpanFull(),

                AuditableView::make('Audit')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('logo')
                    ->disk('public')
                    ->extraImgAttributes(['title' => 'Company logo']),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->limit(30),
                TextColumn::make('views'),
                ToggleColumn::make('is_active')
                    ->onColor('success')
                    ->offColor('danger'),

                BadgeColumn::make('link')
                    ->limit(10)
                    ->copyable()
                    ->copyMessage('Link copied')
                    ->copyMessageDuration(1500),

            ])->defaultSort('name')
            ->filters([
                //is active filter
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
