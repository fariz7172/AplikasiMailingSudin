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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kontrak')->unique();
            $table->string('tgl_kontrak')->nullable();
            $table->bigInteger('nilai_kontrak')->default(0);
            $table->string('addendum_kontrak')->nullable();
            $table->string('tgl_addendum')->nullable();
            $table->bigInteger('nilai_addendum1')->default(0);
            $table->string('addendum_kontrak2')->nullable();
            $table->string('tgl_addendum2')->nullable();
            $table->bigInteger('nilai_addendum2')->default(0);
            $table->string('jangka_waktu')->nullable();
            $table->string('tahun_tdp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
