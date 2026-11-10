<?php

namespace Database\Seeders;

use App\Enums\Gender;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriminalSeeder extends Seeder
{
    private const COUNT = 200;

    private const NICKNAMES_COMMON = [
        'Тихий', 'Малой', 'Седой', 'Рыжий', 'Косой', 'Слепой', 'Молчун',
        'Профессор', 'Доктор', 'Артист', 'Монах', 'Француз', 'Цыган',
        'Хромой', 'Шрам', 'Лысый', 'Бык', 'Кувалда', 'Гвоздь', 'Крот',
        'Борода', 'Резаный', 'Зубило', 'Угрюмый', 'Танк', 'Кот', 'Бес',
        'Батон', 'Сивый', 'Малыш', 'Туз', 'Кирпич', 'Шило', 'Грач',
    ];

    private const NICKNAMES_FEMALE = [
        'Рыжая', 'Кукла', 'Лиса', 'Цыганка', 'Змея', 'Ласточка', 'Белка',
        'Чёрная', 'Малая', 'Тихоня', 'Вдова', 'Мышь', 'Сорока', 'Кошка',
        'Королева', 'Тень', 'Графиня',
    ];

    public function run(): void
    {
        $faker = FakerFactory::create('ru_RU');

        $hairColorIds = DB::table('hair_colors')->pluck('id')->all();
        $eyeColorIds  = DB::table('eye_colors')->pluck('id')->all();
        $cityIds      = DB::table('cities')->pluck('id')->all();

        $pick = fn (array $ids) => $ids ? $faker->randomElement($ids) : null;

        $rows = [];

        for ($i = 0; $i < self::COUNT; $i++) {
            // Значения enum совпадают с 'male' / 'female' в Faker
            $gender = $faker->randomElement(Gender::cases());

            // CHECK: has_head_hair OR hair_color_id IS NULL
            $hasHair = $faker->boolean(85);

            $nicknames = $gender === Gender::Female
                ? array_merge(self::NICKNAMES_FEMALE, self::NICKNAMES_FEMALE, self::NICKNAMES_COMMON)
                : self::NICKNAMES_COMMON;

            $rows[] = [
                // Всегда известны (NOT NULL)
                'last_name'     => $faker->lastName($gender->value),
                'first_name'    => $faker->firstName($gender->value),
                'gender'        => $gender->value,

                // Может отсутствовать
                'middle_name'   => $faker->boolean(75) ? $faker->middleName($gender->value) : null,
                'nickname'      => $faker->boolean(40) ? $faker->randomElement($nicknames) : null,

                'height_cm'     => $faker->boolean(95) ? $faker->numberBetween(150, 205) : null,
                'weight_kg'     => $faker->boolean(90) ? $faker->randomFloat(1, 45, 130) : null,

                'has_head_hair' => $hasHair,
                'hair_color_id' => $hasHair ? $pick($hairColorIds) : null,
                'eye_color_id'  => $pick($eyeColorIds),

                'birth_date'    => $faker->boolean(90)
                    ? $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d')
                    : null,
                'birth_city_id' => $faker->boolean(85) ? $pick($cityIds) : null,

                'notes'         => $faker->boolean(30) ? $faker->sentence(10) : null,
            ];
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('criminals')->insert($chunk);
        }
    }
}
