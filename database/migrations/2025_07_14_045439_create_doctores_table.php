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
        Schema::create('doctores', function (Blueprint $table) {
        $table->string('codigoDoctor', 13)->primary();
        $table->string('nombre', 50);
        $table->string('apellido', 50);
        $table->unsignedBigInteger('user_id')->unique();
        $table->unsignedBigInteger('codigoEspecialidad');
        $table->time('horarioInicio');
        $table->time('horarioFin');
        $table->string('telefono', 8);
        $table->timestamps();
        
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('codigoEspecialidad')->references('codigoEspecialidad')->on('especialidades');
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctores');
    }
};
