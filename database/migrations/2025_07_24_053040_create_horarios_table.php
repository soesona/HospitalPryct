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
        Schema::create('horarios', function (Blueprint $table) {
    $table->id('codigoHorario');
    $table->unsignedBigInteger('codigoDoctor');
    $table->enum('diaSemana', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
    $table->time('horaInicio');
    $table->time('horaFin');
    $table->timestamps();

    $table->foreign('codigoDoctor')->references('codigoDoctor')->on('doctores')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
