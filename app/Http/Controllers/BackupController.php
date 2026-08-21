<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupController extends Controller
{
    /**
     * Membuat file backup database menggunakan mysqldump dan mengirimkannya sebagai file download.
     */
    public function download()
    {
        // Pastikan hanya superadmin/admin yang boleh mengakses fitur ini (sudah dijaga di route)
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');

        // Nama file hasil backup
        $fileName = 'backup_db_' . date('Y_m_d_His') . '.sql';
        
        // Lokasi penyimpanan sementara di folder storage
        $storagePath = storage_path('app/' . $fileName);

        // Bentuk command mysqldump
        // Catatan: Pastikan mysqldump bisa diakses dari env/terminal pada server Anda
        $dumpBinary = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'C:\xampp\mysql\bin\mysqldump.exe' : 'mysqldump';
        
        if (empty($password)) {
            $command = "\"{$dumpBinary}\" -h {$host} -u {$username} {$database} > \"{$storagePath}\" 2>&1";
        } else {
            $command = "\"{$dumpBinary}\" -h {$host} -u {$username} -p\"{$password}\" {$database} > \"{$storagePath}\" 2>&1";
        }

        // Eksekusi command tersebut
        exec($command, $output, $returnVar);

        // Cek jika proses berhasil (0)
        if ($returnVar !== 0) {
            return back()->with('error', 'Gagal membuat backup database. Pastikan perintah mysqldump tersedia di server Anda.');
        }

        // Return file sebagai file yang didownload, dan secara otomatis hapus setelah berhasil di-download
        return response()->download($storagePath)->deleteFileAfterSend(true);
    }
}
