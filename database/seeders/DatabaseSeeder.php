<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CountrySeeder::class,
            CitySeeder::class,
            LanguageSeeder::class,
            OffenseCategorySeeder::class,
            HairColorSeeder::class,
            EyeColorSeeder::class,
            CriminalSeeder::class,
            CriminalSightingSeeder::class,
            SpokenLanguageSeeder::class,
            CitizenshipSeeder::class,
            CriminalOffenseSeeder::class,
        ]);
    }
}
