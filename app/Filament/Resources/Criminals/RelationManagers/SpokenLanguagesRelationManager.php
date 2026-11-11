<?php

namespace App\Filament\Resources\Criminals\RelationManagers;

use App\Constants\SpokenLanguageConstraints;
use App\Enums\LanguageLevel;
use App\Models\Language;
use App\Models\SpokenLanguage;
use App\Rules\UniqueSpokenLanguage;
use App\Support\Database\UniqueConstraintHandler;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;

class SpokenLanguagesRelationManager extends RelationManager
{
    protected static string $relationship = 'spokenLanguages';

    protected static ?string $title = 'Владение языками';

    protected static ?string $modelLabel = 'язык';

    protected static ?string $pluralModelLabel = 'языков';


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('language_id')
                    ->label('Язык')
                    ->required()
                    ->searchable()
                    ->options(
                        Language::query()
                            ->pluck('name', 'id')
                            ->all()
                    )
                    ->rules(fn () => [
                        new UniqueSpokenLanguage(
                            $this->getOwnerRecord()->getKey(),
                        ),
                    ]),
                Select::make('level')
                    ->label('Уровень владения')
                    ->options(LanguageLevel::options())
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('language_id')
            ->columns([
                TextColumn::make('language.name')
                    ->label('Язык')
                    ->searchable(),

                TextColumn::make('level')
                    ->label('Уровень')
                    ->badge()
                    ->formatStateUsing(fn ($state) =>
                        LanguageLevel::tryFrom($state)?->label() ?? $state
                    ),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Добавить язык')
                    ->modalHeading('Добавить язык')
                    ->using(function (array $data, CreateAction $action) {
                        try {
                            return $this->getRelationship()->create($data);
                        } catch (QueryException $exception) {
                            $handled = UniqueConstraintHandler::handle($exception, [
                                SpokenLanguageConstraints::CRIMINAL_LANGUAGE_UNIQUE => [
                                    'title' => 'Язык уже добавлен',
                                    'body' => 'У этого преступника уже указан такой язык.',
                                ],
                            ]);

                            if (! $handled) {
                                throw $exception;
                            }

                            $action->halt();
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading(fn (SpokenLanguage $record) => 'Редактировать язык: '.$record->language->name)
                    ->schema(fn (Schema $schema, SpokenLanguage $record) => $schema->components([
                        Select::make('level')
                            ->label('Уровень владения')
                            ->options(LanguageLevel::options())
                    ]))
                    ->using(function (array $data, SpokenLanguage $record, EditAction $action) {
                        try {
                            $record->update($data);

                            return $record;
                        } catch (QueryException $exception) {
                            $handled = UniqueConstraintHandler::handle($exception, [
                                SpokenLanguageConstraints::CRIMINAL_LANGUAGE_UNIQUE => [
                                    'title' => 'Язык уже добавлен',
                                    'body' => 'У этого преступника уже указан такой язык.',
                                ],
                            ]);

                            if (! $handled) {
                                throw $exception;
                            }

                            $action->halt();
                        }
                    }),
                DeleteAction::make(),
            ]);
    }
}
