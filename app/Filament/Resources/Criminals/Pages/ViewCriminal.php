<?php

namespace App\Filament\Resources\Criminals\Pages;

use App\Filament\Resources\Criminals\CriminalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCriminal extends ViewRecord
{
    protected static string $resource = CriminalResource::class;

    public function getTitle(): string
    {
        return 'Просмотр преступника: ' . $this->record->full_name;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
