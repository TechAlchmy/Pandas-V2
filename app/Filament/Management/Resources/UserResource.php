<?php

namespace App\Filament\Management\Resources;

use App\Enums\AuthLevelEnum;
use App\Filament\Management\Resources\UserResource\Pages;
use App\Filament\Management\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Notifications\SendUserConfirmedNotification;
use App\Notifications\SendUserDeniedNotification;
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
                Tables\Filters\TernaryFilter::make('manager')
                    ->queries(
                        true: fn ($query) => $query->has('managers'),
                        false: fn ($query) => $query->doesntHave('managers'),
                    )
                    ->trueLabel('Only Manager')
                    ->falseLabel('Non Manager'),
                Tables\Filters\TrashedFilter::make()
                    ->label('Suspended')
                    ->placeholder('All')
                    ->trueLabel('Suspended')
                    ->falseLabel('Active'),
                Tables\Filters\TernaryFilter::make('organization_verified_at')
                    ->nullable(),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->hidden(fn ($record) => $record->organization_verified_at)
                    ->requiresConfirmation()
                    ->successNotificationTitle('User Verified')
                    ->action(function ($record, $action) {
                        $record->touch('organization_verified_at');
                        $record->notify(new SendUserConfirmedNotification);
                        $action->success();
                    }),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('Deny registration')
                    ->visible(fn ($record) => $record->trashed() || empty($record->organization_verified_at))
                    ->requiresConfirmation()
                    ->successNotificationTitle('User denied')
                    ->successNotification(function ($record, $notification) {
                        $record->notify(new SendUserDeniedNotification);
                        return $notification;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn ($record) => $record->trashed() || empty($record->organization_verified_at))
                    ->modalHeading('Suspend User')
                    ->successNotificationTitle('User Suspended')
                    ->label('Suspend'),
                Tables\Actions\RestoreAction::make(),
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
                Infolists\Components\TextEntry::make('deleted_at')
                    ->color('danger')
                    ->label('Suspended')
                    ->visible(fn ($record) => $record->trashed()),
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
                    Infolists\Components\Actions\Action::make('demote manager')
                        ->visible(fn ($record) => $record->is_manager && filament()->auth()->user()->is_admin)
                        ->hidden(fn ($record) => $record->is(auth()->user()))
                        ->icon('heroicon-m-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($record, $action) {
                            if (! filament()->auth()->user()->is_admin) {
                                $action->halt();
                            }
                            // this will make sure we can audit who demote this manager...
                            $record->managers()
                                ->whereBelongsTo(filament()->getTenant())
                                ->first()
                                ?->delete();

                            $action->success();
                        })
                        ->successNotificationTitle('Manager demoted'),
                    Infolists\Components\Actions\Action::make('suspend')
                        ->hidden(fn ($record) => $record->is_manager || $record->is(filament()->auth()->user()))
                        ->icon('heroicon-m-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($record, $action) {
                            if ($record->is(filament()->auth()->user())) {
                                $action->halt();
                            }

                            if (filament()->auth()->user()->is_admin_or_manager) {
                                $record->delete();

                                $action->success();
                            }
                        })
                        ->successRedirectUrl(fn () => UserResource::getUrl())
                        ->successNotificationTitle('User suspended'),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
