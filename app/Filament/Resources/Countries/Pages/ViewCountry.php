<?php

namespace App\Filament\Resources\Countries\Pages;

use App\Filament\Resources\Countries\CountryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Livewire\Attributes\On;

class ViewCountry extends ViewRecord
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->modalHeading(fn($record) => "Удалить запись: {$record->name}?")
                ->disabled(fn ($record) => $record->cities()->exists())
                ->tooltip(fn ($record) =>
                    $record->cities()->exists()
                        ? 'Сначала удалите все города'
                        : null
                )
        ];
    }

    public function getTitle(): string
    {
        return $this->record->name;
    }

    #[On('cities-updated')]
    public function refreshCountry(): void
    {
        $this->dispatch('$refresh');
    }
}
