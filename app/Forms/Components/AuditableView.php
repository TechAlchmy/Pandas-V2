<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Tabs;
use Filament\Resources\Pages\EditRecord;

class AuditableView extends Tabs
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->columnSpanFull()
            ->visible(fn ($livewire) => $livewire instanceof EditRecord)
            ->childComponents([
                Tabs\Tab::make('Edited By')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('updatedBy.name')
                            ->label('Edited By')
                            ->content(fn ($record) => $record->updatedBy?->name),
                        Placeholder::make('updatedBy.email')
                            ->label('Email')
                            ->content(fn ($record) => $record->updatedBy?->email),
                        Placeholder::make('updated_at')
                            ->label('Last updated at')
                            ->content(fn ($record) => $record->created_at?->format('m/d/Y h:i:s A')),
                    ]),
                Tabs\Tab::make('Created By')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('createdBy.name')
                            ->label('Created by')
                            ->content(fn ($record) => $record->createdBy?->name),
                        Placeholder::make('createdBy.email')
                            ->label('Email')
                            ->content(fn ($record) => $record->createdBy?->email),
                        Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn ($record) => $record->updated_at?->format('m/d/Y h:i:s A')),
                    ]),
                Tabs\Tab::make('Suspended By')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('deletedBy.name')
                            ->label('Suspended by')
                            ->content(fn ($record) => $record->deletedBy?->name),
                        Placeholder::make('deletedBy.email')
                            ->label('Email')
                            ->content(fn ($record) => $record->deletedBy?->email),
                        Placeholder::make('deleted_at')
                            ->label('Suspended at')
                            ->content(fn ($record) => $record->deleted_at?->format('m/d/Y h:i:s A')),
                    ]),
            ]);
    }
}
