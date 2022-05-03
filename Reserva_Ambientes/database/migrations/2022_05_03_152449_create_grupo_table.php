<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo', function (Blueprint $table) {
            $table->id();
            $table->foreignID("id_users")
                  ->nullable()
                  ->constrained("users")
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->foreignID("id_materias")
                  ->nullable()
                  ->constrained("materia")
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->string("nombreGrupo");
            $table->integer("cantidad");
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
        Schema::dropIfExists('grupo');
    }
}
