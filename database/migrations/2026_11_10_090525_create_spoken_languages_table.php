<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spoken_languages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('criminal_id')
                ->constrained('criminals')
                ->cascadeOnDelete();

            $table->foreignId('language_id')
                ->constrained('languages')
                ->restrictOnDelete();

            $table->string('level')->nullable();

            $table->timestampsTz();

            $table->unique(
                ['criminal_id', 'language_id'],
                'spoken_languages_criminal_language_ux'
            );

            $table->index('language_id', 'spoken_languages_language_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spoken_languages');
    }
};
