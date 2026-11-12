<?php

namespace App\Filament\Resources\Criminals\RelationManagers;

use App\Models\OffenseCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OffensesRelationManager extends RelationManager
{
    protected static string $relationship = 'offenses';

    protected static ?string $title = 'Преступления';

    protected static ?string $modelLabel = 'преступление';

    protected static ?string $pluralModelLabel = 'преступления';

    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Форма создания: все поля.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('offense_category_id')
                    ->label('Категория')
                    ->relationship('offenseCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                DatePicker::make('effective_date')
                    ->label('Дата')
                    ->required()
                    ->native(false)
                    ->maxDate(now()),

                self::reasonField(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->defaultSort('effective_date', 'desc')
            ->columns([
                TextColumn::make('effective_date')
                    ->label('Дата')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('offenseCategory.name')
                    ->label('Категория')
                    ->badge(),

                TextColumn::make('reason')
                    ->label('Основание')
                    ->limit(80)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return is_string($state) && mb_strlen($state) > 80
                            ? $state
                            : null;
                    })
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('offense_category_id')
                    ->label('Категория')
                    ->options(fn (): array => OffenseCategory::query()
                        ->whereIn(
                            'id',
                            $this->getOwnerRecord()->offenses()->select('offense_category_id'),
                        )
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->multiple(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                // Редактирование: только описание
                EditAction::make()
                    ->schema([
                        self::reasonField(),
                    ]),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function reasonField(): Textarea
    {
        return Textarea::make('reason')
            ->label('Основание')
            ->required()
            ->rows(4)
            ->columnSpanFull();
    }
}
