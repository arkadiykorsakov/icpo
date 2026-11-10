<?php

namespace App\Filament\Resources\Criminals\RelationManagers;

use App\Enums\LanguageLevel;
use App\Models\Language;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ),
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
            ->recordTitleAttribute('language_id')
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
                    ->modalHeading('Добавить язык'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->modalHeading(fn ($record) => "Удалить запись: {$record->language->name}?"),
            ]);
    }
}
