<?php

namespace App\Filament\Resources\Criminals\Pages;

use App\Filament\Resources\Criminals\CriminalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCriminal extends CreateRecord
{
    protected static string $resource = CriminalResource::class;

    protected static ?string $title = 'Создать преступника';

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! ($data['has_head_hair'] ?? false)) {
            $data['hair_color_id'] = null;
        }

        return $data;
    }
}
