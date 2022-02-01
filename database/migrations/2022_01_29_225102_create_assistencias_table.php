<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assistencias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('mes',2)->nullable();
            $table->integer('ano',4)->nullable();
            $table->integer('reuniao',1)->nullable();
            $table->integer('semana',1)->nullable();
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assistencias');
    }
}
