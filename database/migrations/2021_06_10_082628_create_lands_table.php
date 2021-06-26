<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nop')->unique();
            $table->foreignId('owner_id')->constrained();
            $table->string('rt', 10);
            $table->string('rw', 10);
            $table->string('village', 100);
            $table->string('road', 100);
            $table->bigInteger('determination')->nullable();
            $table->string('sppt_persil_number', 50)->nullable();
            $table->string('block_number', 5)->nullable();
            $table->double('land_area');
            $table->string('land_area_unit', 5);
            $table->double('building_area');
            $table->string('building_area_unit', 5);
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
        Schema::dropIfExists('lands');
    }
}
