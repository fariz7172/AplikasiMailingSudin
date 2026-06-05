<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Tambah FK ke programs dan kegiatans
            // nullable agar data lama tidak terganggu
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null')->after('contract_id');
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatans')->onDelete('set null')->after('program_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropForeign(['kegiatan_id']);
            $table->dropColumn(['program_id', 'kegiatan_id']);
        });
    }
};
