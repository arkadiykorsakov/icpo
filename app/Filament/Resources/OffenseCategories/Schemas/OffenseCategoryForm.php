<?php

namespace App\Filament\Resources\OffenseCategories\Schemas;

use App\Rules\TrimmedNotEmpty;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OffenseCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->label('Название')
                    ->maxLength(255)
                    ->unique()
                    ->trim()
                    ->rules([new TrimmedNotEmpty()])
                    ->required()
                    ->extraInputAttributes([
                        'onInput' => "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);",
                    ]),
                Textarea::make('description')
                    ->columnSpanFull()
                    ->label('Описание')
                    ->autosize()
            ]);
    }
}
