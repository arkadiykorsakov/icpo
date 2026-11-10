<?php

namespace App\Filament\Resources\Criminals\Pages;

use App\Filament\Resources\Criminals\CriminalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCriminal extends EditRecord
{
    protected static string $resource = CriminalResource::class;

    protected static ?string $title = 'Редактировать преступника';

    public function getTitle(): string
    {
        return 'Обновить преступника: ' . $this->record->last_name . ' ' . $this->record->first_name . ' ' . ($this->record->middle_name ?? '');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! ($data['has_head_hair'] ?? false)) {
            $data['hair_color_id'] = null;
        }

        return $data;
    }

}
