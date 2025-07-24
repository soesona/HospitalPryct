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
        $table->id('codigoDoctor');
        $table->unsignedBigInteger('codigoUsuario')->unique();
        $table->unsignedBigInteger('codigoEspecialidad');
        $table->time('horarioInicio');
        $table->time('horarioFin');
        $table->timestamps();
        
        $table->foreign('codigoUsuario')->references('id')->on('users')->onDelete('cascade');
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
