<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnidadesFisicasEjecutorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades_fisicas_ejecutoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cod_nucleo');
            $table->string('codigo_unidad_admin');
            $table->string('descripcion_unidad_admin');
            $table->string('codigo_unidad_ejec');
            $table->string('descripcion_unidad_ejec');
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
        Schema::dropIfExists('unidades_fisicas_ejecutoras');
    }
}
