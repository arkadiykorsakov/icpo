<?php

namespace App\Filament\Resources\Languages\Schemas;

use App\Rules\TrimmedNotEmpty;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LanguageForm
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
                TextInput::make('code')
                    ->columnSpanFull()
                    ->label('Код языка по BCP 47')
                    ->required()
                    ->helperText('en, ru, en-US, pt-BR')
                    ->unique()
                    ->trim()
                    ->minLength(1)
                    ->maxLength(35)
                    ->regex('/^[a-zA-Z]{2,8}(-[a-zA-Z0-9]{1,8})*$/')
                    ->rules([new TrimmedNotEmpty()])
            ]);
    }
}
