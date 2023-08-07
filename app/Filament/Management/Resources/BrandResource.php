<?php

namespace App\Filament\Management\Resources;

use App\Filament\Management\Resources\BrandResource\Pages;
use App\Filament\Management\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use App\Models\BrandOrganization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('brandOrganization.is_active')
                    ->sortable()
                    ->updateStateUsing(function ($state, $record) {
                        BrandOrganization::query()
                            ->updateOrCreate([
                                'brand_id' => $record->getKey(),
                                'organization_id' => filament()->getTenant()->getKey(),
                            ], [
                                'is_active' => $state,
                            ]);
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return Brand::query()
            ->whereIn('id', BrandOrganization::query()->select('brand_id')
                ->whereBelongsTo(filament()->getTenant()))
            ->orDoesntHave('organizations');
    }
}
