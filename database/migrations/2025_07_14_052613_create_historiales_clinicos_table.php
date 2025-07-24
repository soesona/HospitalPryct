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
        Schema::create('historiales_clinicos', function (Blueprint $table) {
        $table->id('codigoHistorial');
        $table->unsignedBigInteger('codigoPaciente');
        $table->unsignedBigInteger('codigoConsulta');
        $table->date('fechaRegistro'); 
        $table->text('descripcion');   
        $table->timestamps();

        $table->foreign('codigoPaciente')->references('codigoPaciente')->on('pacientes')->onDelete('cascade');
        $table->foreign('codigoConsulta')->references('codigoConsulta')->on('consultas')->onDelete('cascade');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiales_clinicos');
    }
};
