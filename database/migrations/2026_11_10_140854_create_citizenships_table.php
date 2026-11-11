<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citizenships', function (Blueprint $table) {
            $table->id();

            $table->foreignId('criminal_id')
                ->constrained('criminals')
                ->cascadeOnDelete();

            $table->foreignId('country_id')
                ->constrained('countries')
                ->restrictOnDelete();

            $table->timestampTz('created_at')->useCurrent();

            $table->unique(
                ['criminal_id', 'country_id'],
                'citizenships_criminal_country_ux'
            );

            $table->index('country_id', 'citizenships_country_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citizenships');
    }
};
