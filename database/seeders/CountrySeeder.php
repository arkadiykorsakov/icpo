<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = json_decode(
            file_get_contents(database_path('data/countries.json')),
            true
        );

        $data = collect($countries)->map(fn ($country) => [
            'name' => $country['name'],
            'iso2' => $country['iso2'],
            'iso3' => $country['iso3'],
            'created_at' => now(),
        ])->all();

        foreach (array_chunk($data, 100) as $chunk) {
            DB::table('countries')->insert($chunk);
        }
    }
}
