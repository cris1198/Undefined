<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAulaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignID("id_administrador")
                  ->nullable()
                  ->constrained("administrador")
                  ->cascadeOnUpdate()
                  ->nullOnDelete();
            $table->integer("capacidad");
            $table->string("codigo");
            $table->string("tipo");
            $table->text("caracteristicas");
            $table->string("nombreAula");
            $table->string("ubicacion");
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
        Schema::dropIfExists('aula');
    }
}
