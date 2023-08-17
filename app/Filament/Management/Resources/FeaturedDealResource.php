<?php

namespace App\Filament\Management\Resources;

use App\Filament\Management\Resources\FeaturedDealResource\Pages;
use App\Filament\Management\Resources\FeaturedDealResource\RelationManagers;
use App\Models\Brand;
use App\Models\BrandOrganization;
use App\Models\FeaturedDeal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeaturedDealResource extends Resource
{
    protected static ?string $model = FeaturedDeal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('discount_id')
                    ->required()
                    ->searchable()
                    ->relationship('discount', 'name', function ($query) {
                        return $query->whereIn('brand_id', BrandOrganization::query()
                            ->select('brand_id')
                            ->where('is_active', true)
                            ->whereBelongsTo(filament()->getTenant()));
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('discount.name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->reorderable('order_column');
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
            'index' => Pages\ListFeaturedDeals::route('/'),
            // 'create' => Pages\CreateFeaturedDeal::route('/create'),
            // 'edit' => Pages\EditFeaturedDeal::route('/{record}/edit'),
        ];
    }
}
