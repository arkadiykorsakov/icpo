<?php

namespace App\Filament\Resources\Languages\Pages;

use App\Constants\LanguageConstraints;
use App\Filament\Resources\Languages\LanguageResource;
use App\Support\Database\UniqueConstraintHandler;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;

class CreateLanguage extends CreateRecord
{
    protected static string $resource = LanguageResource::class;

    protected static ?string $title = 'Создать язык';

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return static::getModel()::create($data);
        } catch (UniqueConstraintViolationException $exception) {
            $handled = UniqueConstraintHandler::handle($exception, [
                LanguageConstraints::LOWER_NAME_UNIQUE => [
                    'title' => 'Язык уже существует',
                    'body' => 'Язык с таким названием уже существует.',
                ],
                LanguageConstraints::LOWER_CODE_UNIQUE => [
                    'title' => 'Код языка занят',
                    'body' => 'Язык с таким кодом уже существует.',
                ],
            ]);

            if (! $handled) {
                throw $exception;
            }

            $this->halt();
        }
    }
}
