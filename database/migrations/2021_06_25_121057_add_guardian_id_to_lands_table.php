<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuardianIdToLandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lands', function (Blueprint $table) {
            $table->integer('guardian_id')->after('owner_id');

            $table->foreign('guardian_id')->references('id')->on('guardians')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lands', function (Blueprint $table) {
            //
        });
    }
}
