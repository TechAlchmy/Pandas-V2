<?php

namespace App\Filament\Management\Resources;

use App\Filament\Management\Resources\OrganizationOfferTypeResource\Pages;
use App\Filament\Management\Resources\OrganizationOfferTypeResource\RelationManagers;
use App\Models\OrganizationOfferType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganizationOfferTypeResource extends Resource
{
    protected static ?string $model = OrganizationOfferType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Offer Types';

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
                Tables\Columns\TextColumn::make('offerType.type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->sortable(),
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
            'index' => Pages\ListOrganizationOfferTypes::route('/'),
            // 'create' => Pages\CreateOrganizationOfferType::route('/create'),
            // 'edit' => Pages\EditOrganizationOfferType::route('/{record}/edit'),
        ];
    }
}
