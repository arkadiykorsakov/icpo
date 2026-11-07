<?php

namespace App\Filament\Resources\Countries\Schemas;

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
                    ->trim()
                    ->required()
                    ->maxLength(255)
                    ->unique()
                    ->extraInputAttributes([
                        'onInput' => "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);",
                    ]),

                TextInput::make('iso2')
                    ->columnSpanFull()
                    ->label('ISO 2')
                    ->required()
                    ->trim()
                    ->minLength(2)
                    ->maxLength(2)
                    ->regex('/\A[A-Z]{2}\z/')
                    ->dehydrateStateUsing(
                        fn (?string $state) => strtoupper(trim($state ?? ''))
                    )
                    ->unique()
                    ->extraInputAttributes([
                        'onInput' => 'this.value = this.value.toUpperCase()',
                    ]),

                TextInput::make('iso3')
                    ->columnSpanFull()
                    ->label('ISO 3')
                    ->required()
                    ->trim()
                    ->minLength(3)
                    ->maxLength(3)
                    ->regex('/\A[A-Z]{3}\z/')
                    ->dehydrateStateUsing(
                        fn (?string $state) => strtoupper(trim($state ?? ''))
                    )
                    ->unique()
                    ->extraInputAttributes([
                        'onInput' => 'this.value = this.value.toUpperCase()',
                    ]),
            ]);
    }
}
