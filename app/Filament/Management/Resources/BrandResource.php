<?php

namespace App\Filament\Management\Resources;

use App\Filament\Management\Resources\BrandResource\Pages;
use App\Filament\Management\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use App\Models\BrandOrganization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordRouteKeyName = 'brands.id';

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
                Tables\Filters\SelectFilter::make('brandOrganization')
                    ->options([
                        'Unrelated',
                        'Related',
                    ])
                    ->query(function ($data, $query) {
                        return $query
                            ->when($data['value'] == '0', fn ($query) => $query->whereNull('organization_id'))
                            ->when($data['value'] == '1', fn ($query) => $query->where('organization_id', filament()->getTenant()->getKey()));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\DiscountsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'view' => Pages\ViewBrand::route('/{record}'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return Brand::query()
            ->leftJoinRelationship('brandOrganization', function ($join) {
                $join->where('organization_id', filament()->getTenant()->getKey());
            });
    }
}
