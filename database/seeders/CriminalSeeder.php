<?php

namespace Database\Seeders;

use App\Enums\Gender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CriminalSeeder extends Seeder
{
    private const TOTAL = 1000;
    private const CHUNK = 250;

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

    private const NOTES = [
        'Ранее привлекался к уголовной ответственности.',
        'Имеет связи в криминальной среде.',
        'Склонен к совершению имущественных преступлений.',
        'Находится под наблюдением правоохранительных органов.',
        'Ранее неоднократно задерживался.',
        'Предположительно связан с организованной преступной группой.',
        'Ведёт скрытный образ жизни.',
        'Имеет судимость за кражу.',
        'Может использовать поддельные документы.',
        'Предпочитает действовать в одиночку.',
    ];

    public function run(): void
    {
        $faker = fake('ru_RU');

        $hairColorIds = DB::table('hair_colors')->pluck('id')->all();
        $eyeColorIds  = DB::table('eye_colors')->pluck('id')->all();
        $cityIds      = DB::table('cities')->pluck('id')->all();

        if (! $hairColorIds || ! $eyeColorIds || ! $cityIds) {
            $this->command?->warn('Сначала заполните hair_colors, eye_colors и cities.');

            return;
        }

        $now          = Carbon::now();
        $latestDeath  = Carbon::today()->subDay(); // запас против расхождения часовых поясов
        $rows         = [];

        for ($i = 0; $i < self::TOTAL; $i++) {
            $gender = $faker->randomElement(Gender::cases());

            // --- Рождение ---
            $birthDate = $faker->boolean(95)
                ? Carbon::instance($faker->dateTimeBetween('-85 years', '-18 years'))->startOfDay()
                : null;

            // --- Смерть (>= рождения, <= вчера) ---
            $deathDate = null;

            if ($faker->boolean(25)) {
                $from = $birthDate
                    ? $birthDate->copy()->addYears(18)
                    : Carbon::today()->subYears(30);

                if ($from->lt($latestDeath)) {
                    $deathDate = Carbon::instance(
                        $faker->dateTimeBetween($from, $latestDeath)
                    )->startOfDay();
                }
            }

            // --- Волосы ---
            $hasHair = $faker->boolean(85);

            // --- Служебные даты ---
            $createdAt = Carbon::instance($faker->dateTimeBetween('-2 years', 'now'));

            // Soft delete только при наличии даты смерти
            $deletedAt = null;

            if ($deathDate !== null && $faker->boolean(40)) {
                $start = $createdAt->copy()->max($deathDate);

                if ($start->lt($now)) {
                    $deletedAt = Carbon::instance($faker->dateTimeBetween($start, $now));
                }
            }

            $nicknames = $gender === Gender::Female
                ? array_merge(self::NICKNAMES_FEMALE, self::NICKNAMES_COMMON)
                : self::NICKNAMES_COMMON;

            $rows[] = [
                'last_name'   => $faker->lastName($gender->value),
                'first_name'  => $faker->firstName($gender->value),
                'middle_name' => $faker->boolean(75) ? $faker->middleName($gender->value) : null,
                'nickname'    => $faker->boolean(40) ? $faker->randomElement($nicknames) : null,

                'gender'    => $gender->value,
                'height_cm' => $faker->boolean(95) ? $faker->numberBetween(150, 205) : null,
                'weight_kg' => $faker->boolean(90) ? $faker->randomFloat(1, 45, 130) : null,

                'has_head_hair' => $hasHair,
                'hair_color_id' => $hasHair ? $faker->randomElement($hairColorIds) : null,
                'eye_color_id'  => $faker->boolean(95) ? $faker->randomElement($eyeColorIds) : null,

                'birth_date'    => $birthDate?->toDateString(),
                'birth_city_id' => $birthDate ? $faker->randomElement($cityIds) : null,

                'death_date'    => $deathDate?->toDateString(),
                'death_city_id' => $deathDate ? $faker->randomElement($cityIds) : null,

                // умершие архивируются чаще
                'is_archived' => $deathDate ? $faker->boolean(60) : $faker->boolean(10),

                'notes' => $faker->boolean(30) ? $faker->randomElement(self::NOTES) : null,

                'created_at' => $createdAt,
                'updated_at' => $deletedAt ?? $createdAt,
                'deleted_at' => $deletedAt,
            ];

            if (count($rows) >= self::CHUNK) {
                DB::table('criminals')->insert($rows);
                $rows = [];
            }
        }

        if ($rows) {
            DB::table('criminals')->insert($rows);
        }
    }
}
