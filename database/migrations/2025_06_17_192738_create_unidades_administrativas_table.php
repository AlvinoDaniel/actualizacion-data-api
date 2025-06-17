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
            $table->string('codigo_unidad_anterior');
            $table->string('descripcion');
            $table->string('correo_dependencia')->nullable();
            $table->boolean('activo')->default(1);
            $table->foreignId('id_unidad_ejec');
            $table->foreignId('id_escuela')->nullable();
            $table->foreignId('id_unidad_padre')->nullable();
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
