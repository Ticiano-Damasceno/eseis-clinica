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
        Schema::create('horario_funcionamento', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger('dia_semana');

            $table->time('horario_inicio');
            $table->time('horario_fim');

            $table->timestamps();

            $table->unique(
                ['dia_semana', 'horario_inicio'],
                'funcionamento_dia_inicio_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horario_funcionamento');
    }
};
