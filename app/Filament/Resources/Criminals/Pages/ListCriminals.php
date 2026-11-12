<?php

namespace App\Filament\Resources\Criminals\Pages;

use App\Filament\Resources\Criminals\CriminalResource;
use App\Models\Criminal;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListCriminals extends ListRecords
{
    protected static string $resource = CriminalResource::class;

    protected static ?string $title = 'Преступники';

    /** @var array{active: int, archived: int, deleted: int}|null */
    private ?array $tabCounts = null;

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

    public function getTabs(): array
    {
        return [
            'active' => Tab::make('Активные')
                ->icon(Heroicon::Users)
                ->badge(fn (): int => $this->getTabCounts()['active'])
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->whereNull('criminals.deleted_at')
                        ->where('criminals.is_archived', false)
                ),

            'archived' => Tab::make('Архив')
                ->icon(Heroicon::ArchiveBox)
                ->badge(fn (): int => $this->getTabCounts()['archived'])
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->whereNull('criminals.deleted_at')
                        ->where('criminals.is_archived', true)
                ),

            'deleted' => Tab::make('Удалённые')
                ->icon(Heroicon::Trash)
                ->badge(fn (): int => $this->getTabCounts()['deleted'])
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->whereNotNull('criminals.deleted_at')
                ),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'active';
    }

    /**
     * Один запрос на все три бейджа.
     *
     * @return array{active: int, archived: int, deleted: int}
     */
    private function getTabCounts(): array
    {
        if ($this->tabCounts !== null) {
            return $this->tabCounts;
        }

        $row = Criminal::withTrashed()
            ->toBase()
            ->selectRaw('
                count(*) filter (where deleted_at is null and not is_archived) as active,
                count(*) filter (where deleted_at is null and is_archived)     as archived,
                count(*) filter (where deleted_at is not null)                 as deleted
            ')
            ->first();

        return $this->tabCounts = [
            'active'   => (int) $row->active,
            'archived' => (int) $row->archived,
            'deleted'  => (int) $row->deleted,
        ];
    }
}
