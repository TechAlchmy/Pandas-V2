<?php

namespace App\Filament\Management\Resources;

use App\Enums\AuthLevelEnum;
use App\Filament\Management\Resources\UserResource\Pages;
use App\Filament\Management\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Employees';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('social_security_number'),
                Forms\Components\TextInput::make('phone_number')
                    ->tel(),
                Forms\Components\TextInput::make('address'),
                Forms\Components\TextInput::make('city'),
                Forms\Components\TextInput::make('state'),
                Forms\Components\TextInput::make('zip_code')
                    ->numeric(),
                Forms\Components\TextInput::make('country'),
                Forms\Components\TextInput::make('profile_picture'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orders_count')
                    ->sortable()
                    ->counts('orders'),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('email'),
                Infolists\Components\TextEntry::make('phone_number'),
                Infolists\Components\TextEntry::make('city'),
                Infolists\Components\TextEntry::make('state'),
                Infolists\Components\TextEntry::make('country'),
                Infolists\Components\TextEntry::make('country')
                    ->label('Orders Count')
                    ->formatStateUsing(function ($record) {
                        return $record->loadCount('orders')->orders_count;
                    }),
                Infolists\Components\TextEntry::make('address')
                    ->columnSpanFull(),
                Infolists\Components\Actions::make([
                    Infolists\Components\Actions\Action::make('removeFromCompany')
                        ->icon('heroicon-m-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            if ($record->auth_level == AuthLevelEnum::Manager) {
                                $manager = $record->managers()
                                    ->where('organization_id', filament()->getTenant()->getKey())
                                    ->first();

                                $manager->delete();
                                if ($record->managers()->exists()) {
                                    $record->update([
                                        'organization_id' => $record->managers()->first()->organization_id,
                                    ]);
                                } else {
                                    $record->update([
                                        'organization_id' => null,
                                        'auth_level' => AuthLevelEnum::User
                                    ]);
                                }


                                Notification::make()
                                    ->success()
                                    ->title('Manager removed from this company')
                                    ->send();
                                return;
                            }

                            Notification::make()
                                ->success()
                                ->title('User removed from this company')
                                ->send();

                            return redirect(self::getUrl());
                        }),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
