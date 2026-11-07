<?php

namespace App\Filament\Resources\Countries\Pages;

use App\Constants\CountryConstraints;
use App\Filament\Resources\Countries\CountryResource;
use App\Support\Database\UniqueConstraintHandler;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return static::getModel()::create($data);
        } catch (UniqueConstraintViolationException $exception) {
            $handled = UniqueConstraintHandler::handle($exception, [
                CountryConstraints::LOWER_NAME_UNIQUE => [
                    'title' => 'Страна уже существует',
                    'body' => 'Страна с таким названием уже существует.',
                ],
                CountryConstraints::ISO2_UNIQUE => [
                    'title' => 'Код ISO2 занят',
                    'body' => 'Код ISO2 уже используется другой страной.',
                ],
                CountryConstraints::ISO3_UNIQUE => [
                    'title' => 'Код ISO3 занят',
                    'body' => 'Код ISO3 уже используется другой страной.',
                ],
            ]);

            if (! $handled) {
                throw $exception;
            }

            $this->halt();
        }
    }
}
