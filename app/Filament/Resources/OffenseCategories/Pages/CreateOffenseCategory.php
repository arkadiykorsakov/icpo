<?php

namespace App\Filament\Resources\OffenseCategories\Pages;

use App\Constants\OffenseCategoryConstraints;
use App\Filament\Resources\OffenseCategories\OffenseCategoryResource;
use App\Support\Database\UniqueConstraintHandler;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;

class CreateOffenseCategory extends CreateRecord
{
    protected static string $resource = OffenseCategoryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return static::getModel()::create($data);
        } catch (UniqueConstraintViolationException $exception) {
            $handled = UniqueConstraintHandler::handle($exception, [
                OffenseCategoryConstraints::LOWER_NAME_UNIQUE => [
                    'title' => 'Категория уже существует',
                    'body' => 'Категория преступлений с таким названием уже существует.',
                ],
            ]);

            if (! $handled) {
                throw $exception;
            }

            $this->halt();
        }
    }
}
