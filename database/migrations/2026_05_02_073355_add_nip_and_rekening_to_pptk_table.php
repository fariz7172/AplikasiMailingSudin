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
        Schema::table('pptk', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('nama');
            $table->string('no_rekening')->nullable()->after('jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pptk', function (Blueprint $table) {
            $table->dropColumn(['nip', 'no_rekening']);
        });
    }
};
