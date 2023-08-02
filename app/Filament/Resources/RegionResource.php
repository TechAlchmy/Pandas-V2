<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionResource\Pages;
use App\Models\Region;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Utility Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(5),
                Forms\Components\Textarea::make('areas'),
                Forms\Components\TextInput::make('created_by'),
                Forms\Components\TextInput::make('updated_by'),
                Forms\Components\TextInput::make('deleted_by'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('areas'),
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('updated_by'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_by'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListRegions::route('/'),
            'create' => Pages\CreateRegion::route('/create'),
            'edit' => Pages\EditRegion::route('/{record}/edit'),
        ];
    }
}
