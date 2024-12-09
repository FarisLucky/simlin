<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMAlatDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_alat_detail', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('id_alat');
            $table->string('nama', 100);
            $table->smallInteger('jml');
            $table->smallInteger('steril');
            $table->string('status', 30);
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
        Schema::dropIfExists('m_alat_detail');
    }
}
