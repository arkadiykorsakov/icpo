<?php

namespace App\Filament\Resources\Languages\Schemas;

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
                    ->trim()
                    ->required()
                    ->maxLength(255)
                    ->unique()
                    ->extraInputAttributes([
                        'onInput' => "
                            this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
                        ",
                    ]),

                TextInput::make('code')
                    ->columnSpanFull()
                    ->label('Код языка по BCP 47')
                    ->helperText('en, ru, en-US, pt-BR')
                    ->trim()
                    ->required()
                    ->maxLength(35)
                    ->regex('/^[a-zA-Z]{2,8}(-[a-zA-Z0-9]{1,8})*$/')
                    ->unique(),
            ]);
    }
}
