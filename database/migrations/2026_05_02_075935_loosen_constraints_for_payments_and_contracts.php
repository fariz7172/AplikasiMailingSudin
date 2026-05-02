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
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('pptk_id')->nullable()->change();
            $table->unsignedBigInteger('vendor_id')->nullable()->change();
            $table->unsignedBigInteger('contract_id')->nullable()->change();
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->string('nomor_kontrak')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('pptk_id')->nullable(false)->change();
            $table->unsignedBigInteger('vendor_id')->nullable(false)->change();
            $table->unsignedBigInteger('contract_id')->nullable(false)->change();
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->string('nomor_kontrak')->nullable(false)->change();
        });
    }
};
