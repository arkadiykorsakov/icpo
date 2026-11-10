<?php

namespace Database\Seeders;

use App\Enums\LanguageLevel;
use App\Models\Criminal;
use App\Models\Language;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpokenLanguageSeeder extends Seeder
{
    public function run(): void
    {
        $criminalIds = Criminal::pluck('id')->toArray();
        $languageIds = Language::pluck('id')->toArray();

        if (empty($criminalIds) || empty($languageIds)) {
            $this->command->warn(
                'Сначала необходимо заполнить таблицы criminals и languages.'
            );

            return;
        }

        $faker = Factory::create();

        $spokenLanguages = [];

        foreach ($criminalIds as $criminalId) {
            $selectedLanguageIds = $faker->randomElements(
                $languageIds,
                min($faker->numberBetween(1, 3), count($languageIds))
            );

            foreach ($selectedLanguageIds as $languageId) {
                $spokenLanguages[] = [
                    'criminal_id' => $criminalId,
                    'language_id' => $languageId,
                    'level' => $faker->optional(0.9)->randomElement(
                        array_column(LanguageLevel::cases(), 'value')
                    ),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('spoken_languages')->insertOrIgnore($spokenLanguages);
    }
}
