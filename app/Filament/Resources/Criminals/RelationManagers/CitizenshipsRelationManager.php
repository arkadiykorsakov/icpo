<?php

namespace App\Filament\Resources\Criminals\RelationManagers;

use App\Constants\CitizenshipConstraints;
use App\Models\Country;
use App\Rules\UniqueCitizenship;
use App\Support\Database\UniqueConstraintHandler;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;

class CitizenshipsRelationManager extends RelationManager
{
    protected static string $relationship = 'citizenships';

    protected static ?string $title = 'Гражданства';

    protected static ?string $modelLabel = 'гражданство';

    protected static ?string $pluralModelLabel = 'гражданств';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('country_id')
                    ->columnSpanFull()
                    ->label('Гражданство')
                    ->options(
                        Country::query()
                            ->pluck('name', 'id')
                            ->all()
                    )
                    ->required()
                    ->searchable()
                    ->rules(fn () => [
                        new UniqueCitizenship($this->getOwnerRecord()->getKey()),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('country.name')
                    ->label('Гражданство')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Добавить гражданство')
                    ->modalHeading('Добавить гражданство')
                    ->using(function (array $data, CreateAction $action) {
                        try {
                            return $this->getRelationship()->create($data);
                        } catch (QueryException $exception) {
                            $handled = UniqueConstraintHandler::handle($exception, [
                                CitizenshipConstraints::CRIMINAL_COUNTRY_UNIQUE => [
                                    'title' => 'Гражданство уже добавлено',
                                    'body' => 'У этого преступника уже указано такое гражданство.',
                                ],
                            ]);

                            if (! $handled) {
                                throw $exception;
                            }

                            $action->halt();
                        }
                    })
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
