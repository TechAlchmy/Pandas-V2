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
                        Placeholder::make('edited_by')
                            ->content(fn ($record) => $record->updatedBy?->name),
                        Placeholder::make('Email')
                            ->content(fn ($record) => $record->updatedBy?->email),
                        Placeholder::make('Last updated')
                            ->content(fn ($record) => $record->updated_at->format('m/d/Y h:i:s A')),
                    ]),
                Tabs\Tab::make('Created By')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('created_by')
                            ->content(fn ($record) => $record->createdBy?->name),
                        Placeholder::make('email')
                            ->content(fn ($record) => $record->createdBy?->email),
                        Placeholder::make('created_at')
                            ->content(fn ($record) => $record->created_at->format('m/d/Y h:i:s A')),
                    ]),
                Tabs\Tab::make('Deleted By')
                    ->visible(fn ($record) => $record->trashed())
                    ->columns(3)
                    ->schema([
                        Placeholder::make('deleted_by')
                            ->content(fn ($record) => $record->deletedBy?->name),
                        Placeholder::make('email')
                            ->content(fn ($record) => $record->deletedBy?->email),
                        Placeholder::make('created_at')
                            ->content(fn ($record) => $record->deleted_at->format('m/d/Y h:i:s A')),
                    ]),
            ]);
    }
}
