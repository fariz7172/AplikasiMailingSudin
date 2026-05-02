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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Relasi
            $table->foreignId('pptk_id')->constrained('pptk')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');

            // Data Anggaran
            $table->string('no_spd')->nullable();
            $table->string('program')->nullable();
            $table->string('kegiatan')->nullable();
            $table->string('sub_kegiatan')->nullable();
            $table->string('kode_rek')->nullable();

            // Data SPP & SPM
            $table->string('no_spp')->nullable();
            $table->string('no_spm')->nullable();
            $table->date('tgl_spm')->nullable();
            
            // Data SP2D
            $table->string('no_sp2d')->nullable();
            $table->date('tgl_sp2d')->nullable();

            // Data BAST & Kwitansi
            $table->string('nomor_bast')->nullable();
            $table->date('tgl_bast')->nullable();
            $table->string('no_kwi')->nullable();

            // Rincian Tagihan & Nilai
            $table->bigInteger('jumlah')->default(0);
            $table->text('terbilang')->nullable();
            $table->bigInteger('tagihan_1')->default(0);
            $table->bigInteger('tagihan_2')->default(0);
            $table->bigInteger('tagihan_3')->default(0);
            $table->bigInteger('tagihan_4')->default(0);
            $table->bigInteger('tagihan_5')->default(0);

            // Detail Lainnya
            $table->text('keperluan')->nullable();
            $table->bigInteger('denda')->default(0);
            $table->string('progres')->nullable();
            $table->string('nik')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('terbilang_kontrak')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
