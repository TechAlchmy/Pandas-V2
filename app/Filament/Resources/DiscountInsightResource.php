<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountInsightResource\Pages;
use App\Filament\Resources\DiscountInsightResource\Widgets;
use App\Filament\Resources\DiscountInsightResource\RelationManagers;
use App\Models\DiscountInsight;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Infolists;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class DiscountInsightResource extends Resource
{
    protected static ?string $model = DiscountInsight::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('category_id')
                    ->numeric(),
                Forms\Components\TextInput::make('brand_id')
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->numeric(),
                Forms\Components\TextInput::make('term')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('page')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('term')
                    ->searchable(),
                Tables\Columns\TextColumn::make('page')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('terms_with_no_result')
                    ->query(fn ($query) => $query->doesntHave('discountInsightModels')),
                Tables\Filters\SelectFilter::make('category_id')
                    ->preload()
                    ->searchable()
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('brand_id')
                    ->preload()
                    ->searchable()
                    ->relationship('brand', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('results')
                    ->infolist([
                        Infolists\Components\RepeatableEntry::make('discountInsightModels')
                            ->columns()
                            ->grid(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('discount.brand.name')
                                    ->url(fn ($record) => BrandResource::getUrl('edit', ['record' => $record->discount->brand]))
                                    ->openUrlInNewTab(),
                                Infolists\Components\TextEntry::make('discount.name')
                                    ->url(fn ($record) => DiscountResource::getUrl('edit', ['record' => $record->discount]))
                                    ->openUrlInNewTab(),
                                Infolists\Components\TextEntry::make('order_column')
                                    ->label('Seq'),
                            ]),
                    ]),
            ])
            ->bulkActions([
                ExportBulkAction::make(),
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListDiscountInsights::route('/'),
            // 'create' => Pages\CreateDiscountInsight::route('/create'),
            // 'edit' => Pages\EditDiscountInsight::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\DiscountInsightWidget::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('discountInsightModels.discount.brand.media');
    }
}
