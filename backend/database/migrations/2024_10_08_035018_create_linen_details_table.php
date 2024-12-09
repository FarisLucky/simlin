<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinenDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linen_detail', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('nota_linen', 100);
            $table->string('kode_linen', 30);
            $table->string('kode_linen_unit', 30);
            $table->string('nama', 100);
            $table->integer('jml')->comment('jumlah diajukan unit');
            $table->integer('jml_terima')->comment('jumlah diterima cssd');
            $table->integer('jml_kembali')->comment('jumlah dikembalikan ke unit');
            $table->integer('jml_akhir')->comment('jumlah dikonfirmasi unit');
            $table->float('berat');
            $table->dateTime('kembali');
            $table->dateTime('selesai');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('linen_detail');
    }
}
