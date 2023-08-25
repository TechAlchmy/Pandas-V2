<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Forms\Components\AuditableView;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Utility Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->afterStateUpdated(function ($get, $set, ?string $state) {
                        if (! $get('is_slug_changed_manually') && filled($state)) {
                            $set('slug', str($state)->slug());
                        }
                    })
                    ->reactive()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->afterStateUpdated(function ($set) {
                        $set('is_slug_changed_manually', true);
                    })
                    ->required()
                    ->maxLength(255),
                Forms\Components\Hidden::make('is_slug_changed_manually')
                    ->default(false)
                    ->dehydrated(false),
                Forms\Components\TextInput::make('link')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\Card::make()
                    ->columns(3)
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(false),
                        Forms\Components\Placeholder::make('views')
                            ->content(fn ($record) => $record->views ?? 0),
                        Forms\Components\Placeholder::make('Products'),
                    ]),
                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->searchable(),
                AuditableView::make('audit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('views')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
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
