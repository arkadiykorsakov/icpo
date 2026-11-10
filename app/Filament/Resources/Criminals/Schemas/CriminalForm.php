<?php

namespace App\Filament\Resources\Criminals\Schemas;

use App\Enums\Gender;
use App\Models\City;
use App\Models\EyeColor;
use App\Models\HairColor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CriminalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('last_name')
                    ->label('Фамилия')
                    ->trim()
                    ->required()
                    ->maxLength(255)
                    ->extraInputAttributes([
                        'onInput' => "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);",
                    ]),
                TextInput::make('first_name')
                    ->label('Имя')
                    ->trim()
                    ->required()
                    ->maxLength(255)
                    ->extraInputAttributes([
                        'onInput' => "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);",
                    ]),
                TextInput::make('middle_name')
                    ->label('Отчество')
                    ->trim()
                    ->maxLength(255)
                    ->extraInputAttributes([
                        'onInput' => "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);",
                    ]),
                TextInput::make('nickname')
                    ->label('Кличка')
                    ->trim()
                    ->maxLength(255)
                    ->extraInputAttributes([
                        'onInput' => "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);",
                    ]),
                Select::make('gender')
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
                    ->label('Цвет глаз')
                    ->native(false)
                    ->options(EyeColor::query()->pluck('name', 'id')->all())
                    ->searchable(),
                DatePicker::make('birth_date')
                    ->label('Дата рождения')
                    ->maxDate(now()),
                Select::make('birth_city_id')
                    ->label('Город рождения')
                    ->relationship(
                        name: 'birthCity',
                        titleAttribute: 'name',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (City $record) => $record->full_name
                    )
                    ->searchable(),
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
                    ->options(HairColor::query()->pluck('name', 'id')->all())
                    ->searchable()
                    ->visible(fn (Get $get): bool => $get('has_head_hair') === true)
            ]);
    }
}
