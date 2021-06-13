<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutation_histories', function (Blueprint $table) {
            $table->id();
            $table->string('modified_by');
            $table->string('new_taxpayer_name');
            $table->string('new_taxpayer_village');
            $table->string('new_taxpayer_road');
            $table->bigInteger('new_nop');
            $table->integer('guardian_id');
            $table->string('new_tax_object_name');
            $table->string('new_tax_object_road');
            $table->string('sppt_persil_number')->nullable();
            $table->integer('new_land_area');
            $table->string('new_land_area_unit');
            $table->integer('new_building_area');
            $table->string('new_building_area_unit');
            $table->string('taxpayer_source_name');
            $table->string('taxpayer_source_village');
            $table->string('taxpayer_source_road');
            $table->bigInteger('nop_source');
            $table->string('tax_source_object_name');
            $table->string('tax_source_object_road');
            $table->integer('land_source_area');
            $table->string('land_source_area_unit');
            $table->integer('building_source_area');
            $table->string('building_source_area_unit');
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
        Schema::dropIfExists('mutation_histories');
    }
}
