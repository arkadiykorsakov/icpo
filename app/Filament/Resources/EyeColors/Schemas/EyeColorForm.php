<?php

namespace App\Filament\Resources\EyeColors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EyeColorForm
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
            ]);
    }
}
