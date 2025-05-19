<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNucleoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nucleo', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_1',1);
            $table->string('codigo_2',1);
            $table->string('codigo_concatenado',2);
            $table->string('nombre');
            $table->string('direccion', 200);
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
        Schema::dropIfExists('nucleo');
    }
}
