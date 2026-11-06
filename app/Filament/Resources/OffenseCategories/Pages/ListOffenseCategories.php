<?php

namespace App\Filament\Resources\OffenseCategories\Pages;

use App\Filament\Resources\OffenseCategories\OffenseCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOffenseCategories extends ListRecords
{
    protected static string $resource = OffenseCategoryResource::class;

    protected static ?string $title = 'Категории преступлений';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
