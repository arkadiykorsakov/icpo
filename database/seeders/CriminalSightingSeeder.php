<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Criminal;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriminalSightingSeeder extends Seeder
{
    public function run(): void
    {
        $criminalIds = Criminal::pluck('id')->toArray();
        $cityIds = City::pluck('id')->toArray();

        if (empty($criminalIds) || empty($cityIds)) {
            $this->command->warn(
                'Сначала необходимо заполнить таблицы criminals и cities.'
            );

            return;
        }

        $faker = Factory::create();

        $sightings = [];

        for ($i = 0; $i < 50; $i++) {
            $effectiveAt = $faker->dateTimeBetween('-2 years', 'now');

            $sightings[] = [
                'criminal_id' => $faker->randomElement($criminalIds),
                'city_id' => $faker->randomElement($cityIds),
                'description' => $faker->optional(0.8)->sentence(),
                'effective_date' => $effectiveAt->format('Y-m-d'),

                'effective_time' => $faker->optional(0.8)->time('H:i:s'),

                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('criminal_sightings')->insertOrIgnore($sightings);
    }
}

