<?php

namespace App\Support\Database;

use Filament\Notifications\Notification;
use Illuminate\Database\QueryException;

final class UniqueConstraintHandler
{
    public static function handle(
        QueryException $exception,
        array $constraints,
    ): bool {
        $message = $exception->getMessage();

        foreach ($constraints as $constraint => $notification) {
            if (! str_contains($message, $constraint)) {
                continue;
            }

            Notification::make()
                ->danger()
                ->title($notification['title'])
                ->body($notification['body'])
                ->send();

            return true;
        }

        return false;
    }
}
