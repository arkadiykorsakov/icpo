<?php

namespace App\Filament\Resources\Languages\Pages;

use App\Constants\LanguageConstraints;
use App\Filament\Resources\Languages\LanguageResource;
use Filament\Notifications\Notification;
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
            $message = $exception->getMessage();

            $body = match (true) {
                str_contains($message, LanguageConstraints::LOWER_NAME_UNIQUE) => 'Язык с таким названием уже существует.',
                str_contains($message, LanguageConstraints::LOWER_CODE_UNIQUE) => 'Язык с таким кодом уже существует.',
                default => null,
            };

            if ($body === null) {
                throw $exception;
            }

            Notification::make()
                ->danger()
                ->title('Язык уже существует')
                ->body($body)
                ->send();

            $this->halt();
        }
    }
}
