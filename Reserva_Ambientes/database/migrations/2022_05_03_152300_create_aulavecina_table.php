<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAulavecinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aulavecinas', function (Blueprint $table) {
            $table->id();
            $table->foreignID("id_aulas")
                  ->nullable()
                  ->constrained("aulas")
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->string("vecino1");
            $table->string("vecino2");
            $table->string("vecino3");
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
        Schema::dropIfExists('aulavecina');
    }
}
