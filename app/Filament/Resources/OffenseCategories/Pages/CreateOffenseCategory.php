<?php

namespace App\Filament\Resources\OffenseCategories\Pages;

use App\Constants\OffenseCategoryConstraints;
use App\Filament\Resources\OffenseCategories\OffenseCategoryResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;

class CreateOffenseCategory extends CreateRecord
{
    protected static ?string $title = 'Создать категорию';

    protected static string $resource = OffenseCategoryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return static::getModel()::create($data);
        } catch (UniqueConstraintViolationException $exception) {
            $message = $exception->getMessage();

            $body = match (true) {
                str_contains($message, OffenseCategoryConstraints::LOWER_NAME_UNIQUE) => 'Категория с таким названием уже существует.',
                default => null,
            };

            if ($body === null) {
                throw $exception;
            }

            Notification::make()
                ->danger()
                ->title('Категория уже существует')
                ->body($body)
                ->send();

            $this->halt();
        }
    }
}
