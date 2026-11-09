<?php

namespace App\Filament\Resources\EyeColors\Pages;

use App\Filament\Resources\EyeColors\EyeColorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEyeColors extends ListRecords
{
    protected static string $resource = EyeColorResource::class;

    protected static ?string $title = 'Цвет глаз';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
