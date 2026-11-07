<?php

namespace App\Filament\Resources\Countries\Pages;

use App\Constants\CountryConstraints;
use App\Filament\Resources\Countries\CountryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;

    protected static ?string $title = 'Создать страну';

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return static::getModel()::create($data);
        } catch (UniqueConstraintViolationException $exception) {
            $message = $exception->getMessage();

            $body = match (true) {
                str_contains($message, CountryConstraints::LOWER_NAME_UNIQUE)
                => 'Страна с таким названием уже существует.',

                str_contains($message, CountryConstraints::ISO2_UNIQUE)
                => 'Код ISO2 уже используется другой страной.',

                str_contains($message, CountryConstraints::ISO3_UNIQUE)
                => 'Код ISO3 уже используется другой страной.',

                default => null,
            };

            if ($body === null) {
                throw $exception;
            }

            Notification::make()
                ->danger()
                ->title('Страна уже существует')
                ->body($body)
                ->send();

            $this->halt();
        }
    }
}
