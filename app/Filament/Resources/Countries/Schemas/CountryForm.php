<?php

namespace App\Filament\Resources\Countries\Schemas;

use App\Rules\TrimmedNotEmpty;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->label('Название')
                    ->required()
                    ->maxLength(255)
                    ->unique()
                    ->trim()
                    ->rules([new TrimmedNotEmpty()])
                    ->extraInputAttributes([
                        'onInput' => "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);",
                    ]),
                TextInput::make('iso2')
                    ->columnSpanFull()
                    ->label('ISO 2')
                    ->required()
                    ->unique()
                    ->trim()
                    ->minLength(2)
                    ->maxLength(2)
                    ->regex('/^[A-Z]{2}$/')
                    ->rules([new TrimmedNotEmpty(), 'uppercase'])
                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                TextInput::make('iso3')
                    ->columnSpanFull()
                    ->label('ISO 3')
                    ->required()
                    ->unique()
                    ->trim()
                    ->minLength(3)
                    ->maxLength(3)
                    ->regex('/^[A-Z]{3}$/')
                    ->rules([new TrimmedNotEmpty(), 'uppercase'])
                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
            ]);
    }
}
