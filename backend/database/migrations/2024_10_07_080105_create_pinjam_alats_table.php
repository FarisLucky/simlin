<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamAlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pinjam_alat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bundle')->nullable();
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->string('nota', 100);
            $table->string('nama', 100);
            $table->string('bundle', 100);
            $table->integer('jml');
            $table->dateTime('pinjam');
            $table->dateTime('kembali');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('pinjam_alat');
    }
}
