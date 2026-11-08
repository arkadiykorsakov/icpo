<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hair_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestampTz('created_at')->useCurrent();
        });

        DB::statement('
            CREATE UNIQUE INDEX hair_colors_lower_name_ux
            ON hair_colors (LOWER(name))
        ');
    }

    public function down(): void
    {
        Schema::dropIfExists('hair_colors');
    }
};
