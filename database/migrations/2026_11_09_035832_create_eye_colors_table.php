<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eye_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestampTz('created_at')->useCurrent();
        });

        DB::statement('
            CREATE UNIQUE INDEX eye_colors_lower_name_ux
            ON eye_colors (LOWER(name))
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eye_colors');
    }
};
