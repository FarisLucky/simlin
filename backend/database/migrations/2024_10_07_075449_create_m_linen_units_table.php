<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMLinenUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_linen_unit', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('kode', 20);
            $table->string('kode_linen', 5);
            $table->string('kode_unit', 5);
            $table->string('nama', 100);
            $table->smallInteger('jml');
            $table->smallInteger('steril');
            $table->smallInteger('kotor');
            $table->smallInteger('cuci');
            $table->tinyInteger('status');
            $table->unsignedSmallInteger('updated_by');
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
        Schema::dropIfExists('m_linen_unit');
    }
}
