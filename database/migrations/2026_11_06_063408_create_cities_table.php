<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('country_id')
                ->constrained()
                ->restrictOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index('country_id', 'cities_country_id_idx');
        });

        DB::statement('
            CREATE UNIQUE INDEX cities_name_ci
            ON cities (LOWER(name), country_id)
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
