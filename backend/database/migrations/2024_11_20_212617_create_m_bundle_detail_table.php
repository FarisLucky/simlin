<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMBundleDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_bundle_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('id_bundle');
            $table->string('kode_alat', 10);
            $table->string('alat', 50);
            $table->smallInteger('jml');
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
        Schema::dropIfExists('m_bundle_detail');
    }
}
