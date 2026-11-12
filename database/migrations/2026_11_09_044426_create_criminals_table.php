<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criminals', function (Blueprint $table) {
            $table->id();

            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('nickname')->nullable();

            $table->enum('gender', ['male', 'female']);

            $table->integer('height_cm')->nullable();
            $table->decimal('weight_kg', 4, 1)->nullable();

            $table->boolean('has_head_hair')->default(true);

            $table->foreignId('hair_color_id')
                ->nullable()
                ->constrained('hair_colors')
                ->restrictOnDelete();

            $table->foreignId('eye_color_id')
                ->nullable()
                ->constrained('eye_colors')
                ->restrictOnDelete();

            $table->date('birth_date')->nullable();

            $table->foreignId('birth_city_id')
                ->nullable()
                ->constrained('cities')
                ->restrictOnDelete();

            $table->date('death_date')->nullable();

            $table->foreignId('death_city_id')
                ->nullable()
                ->constrained('cities')
                ->restrictOnDelete();

            $table->boolean('is_archived')->default(false);

            $table->text('notes')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['last_name', 'first_name'], 'criminals_last_first_name_idx');
            $table->index('birth_date', 'criminals_birth_date_idx');
            $table->index('birth_city_id', 'criminals_birth_city_id_idx');
            $table->index('hair_color_id', 'criminals_hair_color_id_idx');
            $table->index('eye_color_id', 'criminals_eye_color_id_idx');
            $table->index('death_date', 'criminals_death_date_idx');
            $table->index('death_city_id', 'criminals_death_city_id_idx');
        });

        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');

        DB::statement("
            CREATE INDEX criminals_active_name_idx
            ON criminals (last_name, first_name)
            WHERE deleted_at IS NULL AND is_archived = false
        ");

        DB::statement("
            CREATE INDEX criminals_archived_name_idx
            ON criminals (last_name, first_name)
            WHERE deleted_at IS NULL AND is_archived = true
        ");

        DB::statement("
            CREATE INDEX criminals_deleted_idx
            ON criminals (deleted_at)
            WHERE deleted_at IS NOT NULL
        ");

        DB::statement("
            CREATE INDEX criminals_search_trgm_idx
            ON criminals
            USING gin ((
                last_name
                || ' ' || first_name
                || COALESCE(' ' || middle_name, '')
                || COALESCE(' ' || nickname, '')
            ) gin_trgm_ops)
        ");

        DB::statement("
            ALTER TABLE criminals
            ADD CONSTRAINT criminals_height_cm_check
            CHECK (
                height_cm IS NULL
                OR height_cm BETWEEN 30 AND 280
            )
        ");

        DB::statement("
            ALTER TABLE criminals
            ADD CONSTRAINT criminals_weight_kg_check
            CHECK (
                weight_kg IS NULL
                OR weight_kg BETWEEN 0.5 AND 500
            )
        ");

        DB::statement("
            ALTER TABLE criminals
            ADD CONSTRAINT criminals_head_hair_check
            CHECK (
                has_head_hair
                OR hair_color_id IS NULL
            )
        ");

        DB::statement("
            ALTER TABLE criminals
            ADD CONSTRAINT criminals_birth_date_check
            CHECK (
                birth_date IS NULL
                OR birth_date <= CURRENT_DATE
            )
        ");

        DB::statement("
            ALTER TABLE criminals
            ADD CONSTRAINT criminals_death_date_check
            CHECK (
                death_date IS NULL
                OR (
                    death_date <= CURRENT_DATE
                    AND (
                        birth_date IS NULL
                        OR death_date >= birth_date
                    )
                )
            )
        ");

        DB::statement("
            ALTER TABLE criminals
            ADD CONSTRAINT criminals_deleted_requires_death_check
            CHECK (
                deleted_at IS NULL
                OR death_date IS NOT NULL
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('criminals');
    }
};
