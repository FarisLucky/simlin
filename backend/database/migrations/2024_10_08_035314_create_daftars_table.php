<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaftarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 100);
            $table->integer('jml');
            $table->string('jenis', 10);
            $table->dateTime('pengajuan');
            $table->dateTime('terima');
            $table->dateTime('kembali');
            $table->tinyInteger('status');
            $table->string('ket', 240);
            $table->unsignedSmallInteger('created_by');
            $table->string('created_by_name', 50);
            $table->unsignedSmallInteger('approved_by')->nullable();
            $table->string('approved_by_name', 50)->nullable();
            $table->unsignedSmallInteger('updated_by')->nullable();
            $table->string('updated_by_name', 50)->nullable();
            $table->string('penerima')->nullable();
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
        Schema::dropIfExists('daftar');
    }
}
