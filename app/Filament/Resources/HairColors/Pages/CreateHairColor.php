<?php

namespace App\Filament\Resources\HairColors\Pages;

use App\Constants\HairColorConstraints;
use App\Filament\Resources\HairColors\HairColorResource;
use App\Support\Database\UniqueConstraintHandler;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;

class CreateHairColor extends CreateRecord
{
    protected static string $resource = HairColorResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return static::getModel()::create($data);
        } catch (UniqueConstraintViolationException $exception) {
            $handled = UniqueConstraintHandler::handle($exception, [
                HairColorConstraints::LOWER_NAME_UNIQUE => [
                    'title' => 'Такой цвет волос уже существует',
                    'body' => 'Цвет волос с таким названием уже существует.',
                ],
            ]);

            if (! $handled) {
                throw $exception;
            }

            $this->halt();
        }
    }
}
