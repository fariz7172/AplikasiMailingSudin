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
        Schema::table('contracts', function (Blueprint $table) {
            $table->bigInteger('nilai_kontrak')->nullable()->change();
            $table->bigInteger('nilai_addendum1')->nullable()->change();
            $table->bigInteger('nilai_addendum2')->nullable()->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->bigInteger('jumlah')->nullable()->change();
            $table->bigInteger('tagihan_1')->nullable()->change();
            $table->bigInteger('tagihan_2')->nullable()->change();
            $table->bigInteger('tagihan_3')->nullable()->change();
            $table->bigInteger('tagihan_4')->nullable()->change();
            $table->bigInteger('tagihan_5')->nullable()->change();
            $table->bigInteger('denda')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->bigInteger('nilai_kontrak')->nullable(false)->change();
            $table->bigInteger('nilai_addendum1')->nullable(false)->change();
            $table->bigInteger('nilai_addendum2')->nullable(false)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->bigInteger('jumlah')->nullable(false)->change();
            $table->bigInteger('tagihan_1')->nullable(false)->change();
            $table->bigInteger('tagihan_2')->nullable(false)->change();
            $table->bigInteger('tagihan_3')->nullable(false)->change();
            $table->bigInteger('tagihan_4')->nullable(false)->change();
            $table->bigInteger('tagihan_5')->nullable(false)->change();
            $table->bigInteger('denda')->nullable(false)->change();
        });
    }
};
