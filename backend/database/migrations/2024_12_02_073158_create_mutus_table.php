<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutu', function (Blueprint $table) {
            $table->id();
            $table->string('kode_daftar', 100)->unique();
            $table->date('tgl_daftar');
            $table->string('tdk_noda', 5);
            $table->string('tdk_bau', 5);
            $table->string('tdk_pudar', 5);
            $table->string('tdk_rapi', 5);
            $table->string('tdk_kualitas', 5);
            $table->smallInteger('jml_rusak');
            $table->smallInteger('ttl');
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
        Schema::dropIfExists('mutu');
    }
}
