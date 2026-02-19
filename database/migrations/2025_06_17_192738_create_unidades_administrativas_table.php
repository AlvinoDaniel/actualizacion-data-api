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
        Schema::create('unidades_administrativas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cod_nucleo');
            $table->string('codigo_unidad');
            $table->string('descripcion');
            $table->string('cod_unidad_padre')->nullable();
            $table->boolean('activo')->default(1);
            $table->string('cod_escuela', 4)->nullable();
            $table->string('codigo_unidad_anterior');
            $table->boolean('jefe')->default(0);
            $table->string('correo_dependencia')->nullable();
            $table->string('cod_ejec_anterior');
            $table->foreignId('id_unidad_ejec')->nullable();
            $table->string('año', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades_administrativas');
    }
};
