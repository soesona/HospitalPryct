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
        Schema::create('consultas', function (Blueprint $table) {
          $table->id('codigoConsulta');
    $table->unsignedBigInteger('codigoCita')->unique();
    $table->unsignedBigInteger('codigoPaciente');  
    $table->unsignedBigInteger('codigoDoctor');    
    $table->unsignedBigInteger('codigoEnfermedad');
    $table->text('diagnostico');
    $table->text('observaciones')->nullable();
    $table->timestamps();

    $table->foreign('codigoCita')->references('codigoCita')->on('citas')->onDelete('cascade');
    $table->foreign('codigoPaciente')->references('codigoPaciente')->on('pacientes');
    $table->foreign('codigoDoctor')->references('codigoDoctor')->on('doctores');
    $table->foreign('codigoEnfermedad')->references('codigoEnfermedad')->on('enfermedades');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
