<?php

namespace App\Filament\Resources\EyeColors\Pages;

use App\Constants\EyeColorConstraints;
use App\Filament\Resources\EyeColors\EyeColorResource;
use App\Support\Database\UniqueConstraintHandler;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;

class CreateEyeColor extends CreateRecord
{
    protected static string $resource = EyeColorResource::class;

    protected static ?string $title = 'Создать цвет глаз';

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return static::getModel()::create($data);
        } catch (UniqueConstraintViolationException $exception) {
            $handled = UniqueConstraintHandler::handle($exception, [
                EyeColorConstraints::LOWER_NAME_UNIQUE => [
                    'title' => 'Такой цвет глаз уже существует',
                    'body' => 'Цвет глаз с таким названием уже существует.',
                ],
            ]);

            if (! $handled) {
                throw $exception;
            }

            $this->halt();
        }
    }

}
