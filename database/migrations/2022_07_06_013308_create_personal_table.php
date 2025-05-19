<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->id();
            $table->string('nombres_apellidos');
            $table->string('cedula_identidad');
            $table->string('sexo');
            $table->string('tipo_personal');
            $table->string('cargo_opsu');
            $table->foreignId('cod_nucleo');
            $table->boolean('jefe')->default(0);
            $table->string('cargo_jefe')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->foreignId('area_trabajo');
            $table->foreignId('tipo_calzado');
            $table->foreignId('prenda_extra');
            $table->string('pantalon')->nullable();
            $table->string('camisa')->nullable();
            $table->integer('zapato')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal');
    }
}
