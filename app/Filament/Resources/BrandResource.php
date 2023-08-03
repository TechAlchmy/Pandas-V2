<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Forms\Components\AuditableView;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Region;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

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

                Card::make()
                    ->schema([
                        Toggle::make('status')
                            ->onColor('success')
                            ->offColor('danger'),

                        Placeholder::make('views')->content(function ($record) {
                            return $record && $record->views ? $record->views : 0;
                        }),
                        Placeholder::make('Products')->content(function ($record) {

                        }),

                    ])
                    ->columns(3)
                    ->columnSpan(1),

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
                                    ->multiple(),

                            ]),
                        Tabs\Tab::make('Regions')
                            ->schema([
                                Select::make('Brand Tags')
                                    ->placeholder('Select Brand Regions')
                                    ->relationship('regions', 'name')
                                    ->required()
                                    ->reactive()
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

                TextColumn::make('name')->sortable(),
                TextColumn::make('slug')
                    ->limit(30),
                TextColumn::make('views'),
                ToggleColumn::make('status')
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
                SelectFilter::make('status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',

                    ])
                    ->attribute('status'),
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
            ],
                layout: Layout::AboveContent,
            )
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
}
