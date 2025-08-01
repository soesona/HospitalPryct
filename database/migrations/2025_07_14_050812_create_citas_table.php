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
        Schema::create('citas', function (Blueprint $table) {
    $table->id('codigoCita');
    $table->unsignedBigInteger('codigoPaciente'); 
    $table->unsignedBigInteger('codigoDoctor');   
    $table->date('fechaCita');
    $table->time('horaInicio');
    $table->time('horaFin');
    $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'finalizada'])->default('pendiente');
    $table->timestamps();

    $table->foreign('codigoPaciente')->references('codigoPaciente')->on('pacientes')->onDelete('cascade');
    $table->foreign('codigoDoctor')->references('codigoDoctor')->on('doctores')->onDelete('cascade');


    $table->unique(['codigoDoctor', 'fechaCita', 'horaInicio'], 'unique_doctor_time');
    $table->unique(['codigoPaciente', 'fechaCita'], 'unique_patient_date');

    $table->index(['fechaCita', 'estado']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
