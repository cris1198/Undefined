<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignID("id_users")
                  ->nullable()
                  ->constrained("users")
                  ->cascadeOnUpdate();
                  //->cascadeOnDelete();
            $table->foreignID("id_aulas")
                  ->nullable()
                  ->constrained("aulas")
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->foreignID("id_grupo")
                  ->nullable()
                  ->constrained("grupos")
                  ->cascadeOnUpdate();
            $table->string("codigo");
            $table->string("motivo");
            $table->string("tipo");
            $table->string("observaciones")->nullable($value=true);
            $table->integer("cantidadEstudiantes");
            $table->date("fechaReserva");
            $table->integer("periodo");
            $table->integer("cantidadPeriodo")->nullable($value=true);
            $table->integer("aceptadoRechazado")->nullable($value=true);
            $table->string("razon")->nullable($value=true);
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
        Schema::dropIfExists('reserva');
    }
}
