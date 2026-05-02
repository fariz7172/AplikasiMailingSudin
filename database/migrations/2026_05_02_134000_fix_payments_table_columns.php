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
            if (!Schema::hasColumn('payments', 'tgl_spp')) {
                $table->date('tgl_spp')->nullable()->after('no_spp');
            }
            if (!Schema::hasColumn('payments', 'tgl_spd')) {
                $table->date('tgl_spd')->nullable()->after('no_spd');
            }
            if (!Schema::hasColumn('payments', 'tgl_kwi')) {
                $table->date('tgl_kwi')->nullable()->after('no_kwi');
            }
            if (!Schema::hasColumn('payments', 'no_bast') && Schema::hasColumn('payments', 'nomor_bast')) {
                $table->renameColumn('nomor_bast', 'no_bast');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'no_bast')) {
                $table->renameColumn('no_bast', 'nomor_bast');
            }
            $table->dropColumn(['tgl_spp', 'tgl_spd', 'tgl_kwi']);
        });
    }
};
