<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pptk;
use App\Models\Vendor;
use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

try {
    DB::transaction(function() {
        // 1. Ambil PPTK dari Seeder
        $pptk = Pptk::first();
        
        // 2. Simulasi Data Vendor
        $vendor = Vendor::create([
            'nama_perusahaan' => 'PT. TEKNOLOGI MAJU JAYA',
            'direktur' => 'BUDI SANTOSO',
            'npwp' => '01.234.567.8-091.000',
            'akte' => 'AKTE-2026-001',
            'tgl_akte' => '2026-01-15',
            'tdp' => 'TDP-998877',
            'tgl_tdp' => '2026-01-20',
            'bank' => 'BANK DKI',
            'no_rekening' => '123-456-7890',
            'alamat' => 'Jl. Merdeka No. 10, Jakarta Utara',
        ]);

        // 3. Simulasi Data Kontrak
        $contract = Contract::create([
            'nomor_kontrak' => 'KONTRAK/SDA/2026/005',
            'tgl_kontrak' => '2026-02-01',
            'nilai_kontrak' => 500000000, // 500 Juta
            'jangka_waktu' => '90 Hari Kalender',
        ]);

        // 4. Simulasi Data Pembayaran
        $payment = Payment::create([
            'pptk_id' => $pptk->id,
            'vendor_id' => $vendor->id,
            'contract_id' => $contract->id,
            'no_spd' => 'SPD-2026-001',
            'program' => 'Program Pembangunan Infrastruktur',
            'kegiatan' => 'Pembangunan Saluran Air',
            'sub_kegiatan' => 'Pengerukan Lumpur Waduk',
            'kode_rek' => '5.1.02.01.01.0001',
            'no_spp' => 'SPP-001/SDA/2026',
            'no_spm' => 'SPM-001/SDA/2026',
            'tgl_spm' => '2026-03-10',
            'no_sp2d' => 'SP2D-99887766',
            'nomor_bast' => 'BAST-001/2026',
            'tgl_bast' => '2026-03-05',
            'no_kwi' => 'KWI-001',
            'jumlah' => 150000000, // 150 Juta
            'terbilang' => 'Seratus Lima Puluh Juta Rupiah',
            'tagihan_1' => 150000000,
            'keperluan' => 'Pembayaran Termin I (30%) Pekerjaan Pengerukan Lumpur',
            'progres' => '30%',
            'nik' => $pptk->nik,
            'jabatan' => $pptk->jabatan,
        ]);

        echo "SUCCESS: Data berhasil disimpan ke 3 tabel!\n";
        echo "ID Pembayaran: " . $payment->id . "\n";
        echo "Vendor: " . $vendor->nama_perusahaan . "\n";
        echo "Kontrak: " . $contract->nomor_kontrak . "\n";
    });
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
