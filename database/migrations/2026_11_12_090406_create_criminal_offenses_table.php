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
        Schema::create('criminal_offenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('criminal_id')
                ->constrained('criminals')
                ->cascadeOnDelete();

            $table->foreignId('offense_category_id')
                ->constrained('offense_categories')
                ->restrictOnDelete();

            $table->text('reason');

            $table->date('effective_date');

            $table->timestampsTz();

            $table->index(
                ['criminal_id', 'effective_date'],
                'criminal_offenses_criminal_date_idx'
            );

            $table->index(
                'offense_category_id',
                'criminal_offenses_category_idx'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criminal_offenses');
    }
};
