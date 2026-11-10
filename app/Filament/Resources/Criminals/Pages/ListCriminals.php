<?php

namespace App\Filament\Resources\Criminals\Pages;

use App\Filament\Resources\Criminals\CriminalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCriminals extends ListRecords
{
    protected static string $resource = CriminalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function removeTableFilter(
        string $filterName,
        ?string $field = null,
        bool $isRemovingAllFilters = false,
    ): void {
        parent::removeTableFilter($filterName, $field, $isRemovingAllFilters);

        if ($filterName === 'birth_location' && $field === 'birth_country_id') {
            parent::removeTableFilter('birth_location', 'birth_city_id', $isRemovingAllFilters);
        }
    }
}
