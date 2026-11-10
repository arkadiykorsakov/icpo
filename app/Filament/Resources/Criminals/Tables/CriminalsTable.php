<?php

namespace App\Filament\Resources\Criminals\Tables;

use App\Enums\Gender;
use App\Models\City;
use App\Models\Country;
use App\Models\Criminal;
use App\Models\EyeColor;
use App\Models\HairColor;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use EslamRedaDiv\TimezoneDetector\Facades\TimezoneDetector;

class CriminalsTable
{
    public static function configure(Table $table): Table
    {
        $bounds = Criminal::query()
            ->selectRaw('
                MIN(height_cm) AS min_height,
                MAX(height_cm) AS max_height,
                MIN(weight_kg) AS min_weight,
                MAX(weight_kg) AS max_weight,
                MIN(birth_date) AS min_birth_date,
                MAX(birth_date) AS max_birth_date
            ')
            ->first();

        $minHeight = $bounds->min_height;
        $maxHeight = $bounds->max_height;
        $minWeight = $bounds->min_weight;
        $maxWeight = $bounds->max_weight;
        $minBirthDate = $bounds->min_birth_date;
        $maxBirthDate = $bounds->max_birth_date;

        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with([
                'hairColor',
                'eyeColor',
                'birthCity',
            ]))
            ->columns([
                TextColumn::make('last_name')
                    ->label('Фамилия')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('first_name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('middle_name')
                    ->label('Отчество')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nickname')
                    ->label('Кличка')
                    ->searchable(),

                TextColumn::make('gender')
                    ->label('Пол')
                    ->formatStateUsing(fn ($state) => $state instanceof Gender
                        ? $state->label()
                        : (Gender::tryFrom((string) $state)?->label() ?? 'Неизвестно')
                    )
                    ->badge()
                    ->color(fn ($state) => match (
                    $state instanceof Gender
                        ? $state
                        : Gender::tryFrom((string) $state)
                    ) {
                        Gender::Male => Color::Blue,
                        Gender::Female => Color::Pink,
                        default => 'gray',
                    }),

                TextColumn::make('height_cm')
                    ->label('Рост')
                    ->suffix(' см')
                    ->sortable(),

                TextColumn::make('weight_kg')
                    ->label('Вес')
                    ->suffix(' кг')
                    ->sortable(),

                TextColumn::make('has_head_hair')
                    ->label('Волосы на голове')
                    ->formatStateUsing(fn ($state) => $state ? 'Есть' : 'Нет')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                TextColumn::make('hairColor.name')
                    ->label('Цвет волос')
                    ->formatStateUsing(
                        fn ($state, Criminal $record) => ! $record->has_head_hair
                            ? '—'
                            : ($state ?: 'Неизвестно')
                    ),

                TextColumn::make('eyeColor.name')
                    ->label('Цвет глаз')
                    ->placeholder('Неизвестно'),

                TextColumn::make('birth_date')
                    ->label('Дата рождения')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('birthCity.full_name')
                    ->label('Город рождения')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->toUserTimezone(),

                TextColumn::make('updated_at')
                    ->label('Обновлён')
                    ->dateTime('d.m.Y H:i')
                    ->toUserTimezone(),
            ])
            ->filters([
                Filter::make('birth_location')
                    ->label('Место рождения')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('birth_country_id')
                                    ->label('Страна рождения')
                                    ->options(fn () => Country::query()
                                        ->whereHas('birthCriminals')
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->all()
                                    )
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(
                                        fn (Set $set) => $set('birth_city_id', null)
                                    ),

                                Select::make('birth_city_id')
                                    ->label('Город рождения')
                                    ->options(fn (Get $get) => $get('birth_country_id')
                                        ? City::query()
                                            ->where('country_id', $get('birth_country_id'))
                                            ->whereHas('birthCriminals')
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                            ->all()
                                        : []
                                    )
                                    ->searchable()
                                    ->disabled(fn (Get $get) => ! $get('birth_country_id')),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        $countryId = $data['birth_country_id'] ?? null;

                        // Нет страны — нет и индикатора города.
                        if (! $countryId) {
                            return $indicators;
                        }

                        if ($country = Country::find($countryId)) {
                            $indicators[] = Indicator::make(
                                'Страна: ' . $country->name
                            )->removeField('birth_country_id');
                        }

                        if ($cityId = ($data['birth_city_id'] ?? null)) {
                            if ($city = City::find($cityId)) {
                                $indicators[] = Indicator::make(
                                    'Город: ' . $city->name
                                )->removeField('birth_city_id');
                            }
                        }

                        return $indicators;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        $countryId = $data['birth_country_id'] ?? null;
                        $cityId = $data['birth_city_id'] ?? null;

                        // Без страны город игнорируем.
                        if (! $countryId) {
                            return $query;
                        }

                        return $query
                            ->whereHas(
                                'birthCity',
                                fn (Builder $cityQuery) => $cityQuery->where(
                                    'country_id',
                                    $countryId
                                )
                            )
                            ->when(
                                $cityId,
                                fn (Builder $q) => $q->where('birth_city_id', $cityId)
                            );
                    }),

                static::rangeFilter(
                    name: 'height',
                    label: 'Рост',
                    column: 'height_cm',
                    unit: 'см',
                    min: $minHeight,
                    max: $maxHeight,
                    step: 1,
                ),

                static::rangeFilter(
                    name: 'weight',
                    label: 'Вес',
                    column: 'weight_kg',
                    unit: 'кг',
                    min: $minWeight,
                    max: $maxWeight,
                    step: 0.1,
                ),

                Filter::make('birth_date')
                    ->label('Дата рождения')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('from')
                                    ->label('Дата рождения С')
                                    ->minDate($minBirthDate)
                                    ->maxDate($maxBirthDate),

                                DatePicker::make('to')
                                    ->label('Дата рождения По')
                                    ->minDate($minBirthDate)
                                    ->maxDate($maxBirthDate),
                            ]),
                    ])
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = Indicator::make(
                                'Дата рождения с: ' . $data['from']
                            )->removeField('from');
                        }

                        if ($data['to'] ?? null) {
                            $indicators[] = Indicator::make(
                                'Дата рождения по: ' . $data['to']
                            )->removeField('to');
                        }

                        return $indicators;
                    })
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['from'] ?? null,
                            fn (Builder $q, $date) => $q->whereDate('birth_date', '>=', $date)
                        )
                        ->when(
                            $data['to'] ?? null,
                            fn (Builder $q, $date) => $q->whereDate('birth_date', '<=', $date)
                        )
                    ),

                TernaryFilter::make('has_head_hair')
                    ->label('Волосы на голове')
                    ->placeholder('Все')
                    ->trueLabel('Есть')
                    ->falseLabel('Нет'),

                SelectFilter::make('gender')
                    ->label('Пол')
                    ->options(Gender::options()),

                SelectFilter::make('hair_color_id')
                    ->label('Цвет волос')
                    ->options(fn () => HairColor::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all()
                    )
                    ->multiple(),

                SelectFilter::make('eye_color_id')
                    ->label('Цвет глаз')
                    ->options(fn () => EyeColor::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all()
                    )
                    ->multiple(),
            ], FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }

    /**
     * Фильтр-диапазон из двух полей «От» и «До».
     * Минимум и максимум из данных показываются как подсказки.
     */
    protected static function rangeFilter(
        string $name,
        string $label,
        string $column,
        string $unit,
        int|float|string|null $min,
        int|float|string|null $max,
        int|float $step = 1,
    ): Filter {
        $decimals = str_contains((string) $step, '.')
            ? strlen(explode('.', (string) $step)[1])
            : 0;

        $format = function ($value) use ($decimals): ?string {
            if ($value === null) {
                return null;
            }

            if ($decimals === 0) {
                return number_format((float) $value, 0, '.', '');
            }

            return rtrim(
                rtrim(number_format((float) $value, $decimals, '.', ''), '0'),
                '.'
            );
        };

        $extraAttributes = [];
        if ($min !== null && $max !== null) {
            $minJs = (float) $min;
            $maxJs = (float) $max;
            $extraAttributes = [
                'onblur' => "if (this.value === '') return; "
                    . "const v = parseFloat(this.value); if (isNaN(v)) return; "
                    . "const c = Math.min(Math.max(v, {$minJs}), {$maxJs}); "
                    . "if (c !== v) { this.value = c; this.dispatchEvent(new Event('input', { bubbles: true })); }",
            ];
        }

        $baseRules = ['numeric'];
        if ($min !== null) $baseRules[] = 'gte:' . $min;
        if ($max !== null) $baseRules[] = 'lte:' . $max;

        return Filter::make($name)
            ->label($label)
            ->columnSpanFull()
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('from')
                        ->label("{$label} от ({$unit})")
                        ->numeric()
                        ->minValue($min)
                        ->maxValue($max)
                        ->step($step)
                        ->placeholder($format($min))
                        ->extraInputAttributes($extraAttributes)
                        ->rules($baseRules),

                    TextInput::make('to')
                        ->label("{$label} до ({$unit})")
                        ->numeric()
                        ->minValue($min)
                        ->maxValue($max)
                        ->step($step)
                        ->placeholder($format($max))
                        ->extraInputAttributes($extraAttributes)
                        ->rules($baseRules),
                ]),
            ])
            ->indicateUsing(function (array $data) use ($label, $unit): array {
                $indicators = [];
                foreach (['from', 'to'] as $key) {
                    if (($data[$key] ?? null) !== null && $data[$key] !== '') {
                        $prefix = $key === 'from' ? 'от' : 'до';
                        $indicators[] = Indicator::make(
                            "{$label} {$prefix}: {$data[$key]} {$unit}"
                        )->removeField($key);
                    }
                }
                return $indicators;
            })
            ->query(function (Builder $query, array $data) use ($column): Builder {
                $from = $data['from'] ?? null;
                $to   = $data['to']   ?? null;
                $hasFrom = $from !== null && $from !== '';
                $hasTo   = $to   !== null && $to   !== '';

                if ($hasFrom && $hasTo && (float) $from > (float) $to) {
                    return $query->whereRaw('1 = 0');
                }

                return $query
                    ->when($hasFrom, fn (Builder $q) => $q->where($column, '>=', $from))
                    ->when($hasTo,   fn (Builder $q) => $q->where($column, '<=', $to));
            });
    }
}
