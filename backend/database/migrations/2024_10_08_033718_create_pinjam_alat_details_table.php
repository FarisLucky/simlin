<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamAlatDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pinjam_alat_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_nota', 100);
            $table->string('kode_alat', 10);
            $table->string('nama', 100);
            $table->integer('jml');
            $table->integer('jml_terima')->nullable();
            $table->integer('jml_kembali')->nullable();
            $table->integer('jml_akhir')->nullable();
            $table->dateTime('pinjam');
            $table->dateTime('kembali')->nullable();
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
        Schema::dropIfExists('pinjam_alat_detail');
    }
}
