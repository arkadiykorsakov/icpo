<?php

namespace App\Filament\Resources\HairColors\Pages;

use App\Filament\Resources\HairColors\HairColorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHairColors extends ListRecords
{
    protected static string $resource = HairColorResource::class;

    protected static ?string $title = 'Цвет волос';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
