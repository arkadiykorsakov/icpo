<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criminal_sightings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('criminal_id')
                ->constrained('criminals')
                ->restrictOnDelete();

            $table->foreignId('city_id')
                ->constrained('cities')
                ->restrictOnDelete();

            $table->text('description')->nullable();

            $table->date('effective_date');
            $table->time('effective_time')->nullable();

            $table->timestampsTz();

            $table->index('city_id', 'criminal_sightings_city_id_idx');
            $table->index(
                ['criminal_id', 'effective_date'],
                'criminal_sightings_criminal_id_effective_date_idx'
            );
        });

        DB::statement("
            ALTER TABLE criminal_sightings
            ADD CONSTRAINT criminal_sightings_effective_date_check
            CHECK (effective_date <= CURRENT_DATE)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('criminal_sightings');
    }
};
