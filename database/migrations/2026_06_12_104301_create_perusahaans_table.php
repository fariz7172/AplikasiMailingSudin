<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan')->nullable();
            $table->string('direktur')->nullable();
            $table->string('npwp')->nullable();
            $table->string('akte')->nullable();
            $table->date('tgl_akte')->nullable();
            $table->string('tdp')->nullable();
            $table->date('tgl_tdp')->nullable();
            $table->string('bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->text('alamat')->nullable();
            $table->text('alamat_update')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaans');
    }
};
