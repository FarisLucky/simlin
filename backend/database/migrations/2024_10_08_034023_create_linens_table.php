<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linen', function (Blueprint $table) {
            $table->id();
            $table->string('nota', 100);
            $table->string('kode_daftar', 100);
            $table->string('nama', 100);
            $table->integer('jml')->nullable();
            $table->float('berat')->nullable();
            $table->dateTime('selesai')->nullable();
            $table->dateTime('kembali')->nullable();
            $table->tinyInteger('status');
            $table->unsignedSmallInteger('kd_unit');
            $table->unsignedSmallInteger('created_by');
            $table->unsignedSmallInteger('updated_by');
            $table->string('updated_by_name', 50);
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
        Schema::dropIfExists('linen');
    }
}
