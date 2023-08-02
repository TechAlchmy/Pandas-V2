<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountTagResource\Pages;
use App\Filament\Resources\DiscountTagResource\RelationManagers;
use App\Models\DiscountTag;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountTagResource extends Resource
{
    protected static ?string $model = DiscountTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Products';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('discount_id'),
                Forms\Components\TextInput::make('tag_id'),
                Forms\Components\TextInput::make('created_by'),
                Forms\Components\TextInput::make('updated_by'),
                Forms\Components\TextInput::make('deleted_by'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('discount_id'),
                Tables\Columns\TextColumn::make('tag_id'),
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
            'index' => Pages\ListDiscountTags::route('/'),
            'create' => Pages\CreateDiscountTag::route('/create'),
            'edit' => Pages\EditDiscountTag::route('/{record}/edit'),
        ];
    }    
}