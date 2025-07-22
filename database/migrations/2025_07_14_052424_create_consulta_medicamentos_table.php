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
        Schema::create('consulta_medicamentos', function (Blueprint $table) {
        $table->id('codigoEntrega');
        $table->unsignedBigInteger('codigoConsulta');
        $table->unsignedBigInteger('codigoMedicamento');
        $table->integer('cantidadEntregada');
        $table->timestamps();

        $table->foreign('codigoConsulta')->references('codigoConsulta')->on('consultas')->onDelete('cascade');
        $table->foreign('codigoMedicamento')->references('codigoMedicamento')->on('medicamentos');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulta_medicamentos');
    }
};
