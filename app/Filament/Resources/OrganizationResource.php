<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Forms\Components\AuditableView;
use App\Filament\Resources\OrganizationResource\RelationManagers;
use App\Models\Brand;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Squire\Models\Region;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Organizations';

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
                Forms\Components\TextInput::make('website')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(45),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(45),
                Forms\Components\Select::make('region_id')
                    ->options(Region::query()->where('country_id', 'us')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('brand_id')
                    ->placeholder('Select Brands')
                    ->relationship('brands', 'name')
                    ->live()
                    ->multiple()
                    ->helperText(fn ($state) => count($state) < Brand::query()->count() ? null : 'All selected')
                    ->hintActions([
                        Forms\Components\Actions\Action::make('clear')
                            ->visible(fn ($state) => ! empty($state))
                            ->action(fn ($component) => $component->state([])),
                        Forms\Components\Actions\Action::make('all')
                            ->hidden(fn ($state) => count($state) == Brand::query()->count())
                            ->action(fn ($component) => $component->state(Brand::query()->pluck('id')->all())),
                    ]),
                AuditableView::make('audit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registration_link')
                    ->formatStateUsing(fn () => 'Copy Link')
                    ->copyable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('region_id')
                    ->preload()
                    ->searchable()
                    ->options(Region::query()->where('country_id', 'us')->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('portal')
                    ->url(fn ($record) => route('filament.management.pages.dashboard', ['tenant' => $record]))
                    ->link()
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ManagersRelationManager::class,
            RelationManagers\UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
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
