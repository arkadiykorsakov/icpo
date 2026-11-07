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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 35)->comment('Код языка по BCP 47: en, ru, en-US, pt-BR');;
            $table->timestampTz('created_at')->useCurrent();
        });

        DB::statement('
            CREATE UNIQUE INDEX languages_lower_name_ux
            ON languages (LOWER(name))
        ');

        DB::statement('
            CREATE UNIQUE INDEX languages_lower_code_ux
            ON languages (LOWER(code))
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
