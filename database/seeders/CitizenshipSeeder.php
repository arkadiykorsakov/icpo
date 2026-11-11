<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitizenshipSeeder extends Seeder
{
    public function run(): void
    {
        $criminals = DB::table('criminals')->pluck('id')->toArray();
        $countries = DB::table('countries')->pluck('id')->toArray();

        if (empty($criminals) || empty($countries)) {
            return;
        }

        $citizenships = [];

        foreach ($criminals as $criminalId) {
            // От 1 до 3 гражданств для каждого преступника
            $randomCountries = collect($countries)
                ->shuffle()
                ->take(rand(1, min(3, count($countries))));

            foreach ($randomCountries as $countryId) {
                $citizenships[] = [
                    'criminal_id' => $criminalId,
                    'country_id' => $countryId,
                    'created_at' => now(),
                ];
            }
        }

        DB::table('citizenships')->insertOrIgnore($citizenships);

    }
}
