<?php

namespace App\Filament\Resources\OrganizationResource\RelationManagers;

use App\Enums\AuthLevelEnum;
use App\Models\Manager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManagersRelationManager extends RelationManager
{
    protected static string $relationship = 'managers';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->searchable()
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('user.email'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->label('Demoted')
                    ->placeholder('Active')
                    ->trueLabel('All')
                    ->falseLabel('Demoted')
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Assign new manager')
                    ->label('Assign new manager'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Demote'),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->with('user', fn ($query) => $query->withTrashed())
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ])
            );
    }
}
