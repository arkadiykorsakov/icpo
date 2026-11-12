<?php

namespace App\Filament\Resources\Criminals\Schemas;

use App\Enums\Gender;
use App\Models\City;
use App\Models\EyeColor;
use App\Models\HairColor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CriminalForm
{
    public static function configure(Schema $schema): Schema
    {
        // Первая буква всегда заглавная.
        $capitalize = [
            'onInput' => "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);",
        ];

        return $schema
            ->components([
                TextInput::make('last_name')
                    ->label('Фамилия')
                    ->trim()
                    ->required()
                    ->maxLength(255)
                    ->extraInputAttributes($capitalize),

                TextInput::make('first_name')
                    ->label('Имя')
                    ->trim()
                    ->required()
                    ->maxLength(255)
                    ->extraInputAttributes($capitalize),

                TextInput::make('middle_name')
                    ->label('Отчество')
                    ->trim()
                    ->maxLength(255)
                    ->extraInputAttributes($capitalize),

                TextInput::make('nickname')
                    ->label('Кличка')
                    ->trim()
                    ->maxLength(255)
                    ->extraInputAttributes($capitalize),

                Select::make('gender')
                    ->columnSpanFull()
                    ->label('Пол')
                    ->options(Gender::options())
                    ->required(),

                TextInput::make('height_cm')
                    ->label('Рост (см)')
                    ->numeric()
                    ->integer()
                    ->minValue(30)
                    ->maxValue(280),

                TextInput::make('weight_kg')
                    ->label('Вес (кг)')
                    ->numeric()
                    ->minValue(0.5)
                    ->maxValue(500)
                    ->step(0.1),

                Select::make('eye_color_id')
                    ->columnSpanFull()
                    ->label('Цвет глаз')
                    ->native(false)
                    ->options(fn () => EyeColor::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all()
                    )
                    ->searchable(),

                DatePicker::make('birth_date')
                    ->columnSpanFull()
                    ->label('Дата рождения')
                    ->maxDate(now())
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                        if (blank($state)) {
                            $set('birth_city_id', null);

                            return;
                        }

                        // Дата смерти не может быть раньше даты рождения.
                        $death = $get('death_date');

                        if (filled($death) && $death < $state) {
                            $set('death_date', null);
                            $set('death_city_id', null);
                        }
                    }),

                // Скрытое поле не отправляется формой; при пустой дате рождения
                // город обнуляет событие saving в модели Criminal.
                Select::make('birth_city_id')
                    ->columnSpanFull()
                    ->label('Город рождения')
                    ->relationship(
                        name: 'birthCity',
                        titleAttribute: 'name',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (City $record) => $record->full_name
                    )
                    ->searchable()
                    ->preload()
                    ->visible(fn (Get $get): bool => filled($get('birth_date'))),

                DatePicker::make('death_date')
                    ->columnSpanFull()
                    ->label('Дата смерти')
                    ->minDate(fn (Get $get) => filled($get('birth_date')) ? $get('birth_date') : null)
                    ->maxDate(now())
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        if (blank($state)) {
                            $set('death_city_id', null);
                        }
                    }),

                Select::make('death_city_id')
                    ->columnSpanFull()
                    ->label('Город смерти')
                    ->relationship(
                        name: 'deathCity',
                        titleAttribute: 'name',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (City $record) => $record->full_name
                    )
                    ->searchable()
                    ->preload()
                    ->visible(fn (Get $get): bool => filled($get('death_date'))),

                Textarea::make('notes')
                    ->columnSpanFull()
                    ->label('Особые приметы')
                    ->autosize(),

                Toggle::make('has_head_hair')
                    ->columnSpanFull()
                    ->label('Есть волосы?')
                    ->required()
                    ->default(true)
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (! $state) {
                            $set('hair_color_id', null);
                        }
                    }),

                Select::make('hair_color_id')
                    ->columnSpanFull()
                    ->label('Цвет волос')
                    ->options(fn () => HairColor::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all()
                    )
                    ->searchable()
                    ->visible(fn (Get $get): bool => (bool) $get('has_head_hair')),
            ]);
    }
}
