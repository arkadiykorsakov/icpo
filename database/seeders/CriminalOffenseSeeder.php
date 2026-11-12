<?php

namespace Database\Seeders;

use App\Models\Criminal;
use App\Models\OffenseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriminalOffenseSeeder extends Seeder
{
    private const REASONS = [
        'Приговор суда первой инстанции.',
        'Постановление о возбуждении уголовного дела.',
        'Решение суда, вступившее в законную силу.',
        'Административный протокол, переквалифицированный в уголовное дело.',
        'Явка с повинной, подтверждённая материалами дела.',
        'Результаты оперативно-розыскных мероприятий.',
    ];

    public function run(): void
    {
        $criminalIds = Criminal::query()->pluck('id');
        $categoryIds = OffenseCategory::query()->pluck('id');

        if ($criminalIds->isEmpty() || $categoryIds->isEmpty()) {
            $this->command?->warn('Нет criminals или offense_categories: сначала запустите их сидеры.');

            return;
        }

        $faker = fake('ru_RU');
        $now = now();
        $rows = [];

        foreach ($criminalIds as $criminalId) {
            // У каждого человека от 1 до 4 записей, категории могут повторяться
            // в разные даты.
            foreach (range(1, random_int(1, 4)) as $_) {
                $rows[] = [
                    'criminal_id' => $criminalId,
                    'offense_category_id' => $categoryIds->random(),
                    'reason' => $faker->randomElement(self::REASONS),
                    'effective_date' => $faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('criminal_offenses')->insert($chunk);
        }
    }
}
