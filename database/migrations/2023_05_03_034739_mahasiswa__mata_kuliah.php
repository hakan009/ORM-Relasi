<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Mahasiswa_MataKuliah', function (Blueprint $table) {
            $table->id();
            $table->string('mahasiswa_id');
            $table->foreign('mahasiswa_id')->references('Nim')->on('mahasiswas');
            $table->unsignedBigInteger('matakuliah_id');
            $table->foreign('matakuliah_id')->references('id')->on('matakuliah');
            $table->string('nilai', 10);
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
        Schema::dropIfExists('Mahasiswa_MataKuliah');
    }
};
