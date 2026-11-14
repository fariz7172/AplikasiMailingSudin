<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    /**
     * Membuat dan mendownload backup database SQL murni via PHP (PDO)
     * Tanpa memerlukan fungsi exec() atau mysqldump binary sehingga 100% aman di cPanel/Hostinger.
     */
    public function download()
    {
        $fileName = 'backup_db_' . date('Y_m_d_His') . '.sql';

        $response = new StreamedResponse(function () {
            // Naikkan limit memori dan waktu eksekusi jika database cukup besar
            @ini_set('memory_limit', '512M');
            @set_time_limit(300);

            $handle = fopen('php://output', 'w');

            // Header informasi SQL
            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "-- Backup Database: " . config('database.connections.mysql.database') . "\n");
            fwrite($handle, "-- Tanggal: " . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, "-- Aplikasi Mailing Sudin SDA Jakarta Utara\n");
            fwrite($handle, "-- --------------------------------------------------------\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($handle, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n");
            fwrite($handle, "SET time_zone = '+00:00';\n\n");

            // Ambil daftar seluruh tabel
            $tables = DB::select('SHOW TABLES');
            $dbNameKey = 'Tables_in_' . config('database.connections.mysql.database');

            foreach ($tables as $tableObj) {
                $table = $tableObj->$dbNameKey ?? current((array) $tableObj);

                // Dapatkan struktur pembuatan tabel (CREATE TABLE)
                $createTableResult = DB::select("SHOW CREATE TABLE `{$table}`");
                $createTableSql = $createTableResult[0]->{'Create Table'} ?? '';

                fwrite($handle, "-- --------------------------------------------------------\n");
                fwrite($handle, "-- Struktur dari tabel `{$table}`\n");
                fwrite($handle, "-- --------------------------------------------------------\n");
                fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
                fwrite($handle, $createTableSql . ";\n\n");

                // Dump isi data tabel
                $rows = DB::table($table)->get();
                if ($rows->count() > 0) {
                    fwrite($handle, "-- Dumping data untuk tabel `{$table}`\n");
                    
                    // Batch insert per 100 baris agar hemat memori & cepat
                    $chunks = $rows->chunk(100);
                    foreach ($chunks as $chunk) {
                        $valuesList = [];
                        foreach ($chunk as $row) {
                            $rowArray = (array) $row;
                            $escapedValues = array_map(function ($val) {
                                if (is_null($val)) {
                                    return 'NULL';
                                }
                                // Escape string aman untuk SQL
                                return "'" . addcslashes($val, "\000\n\r\\'\"\032") . "'";
                            }, $rowArray);

                            $valuesList[] = '(' . implode(', ', $escapedValues) . ')';
                        }

                        if (!empty($valuesList)) {
                            $columns = array_keys((array) $chunk->first());
                            $escapedColumns = array_map(function ($col) {
                                return "`{$col}`";
                            }, $columns);

                            fwrite($handle, "INSERT INTO `{$table}` (" . implode(', ', $escapedColumns) . ") VALUES\n");
                            fwrite($handle, implode(",\n", $valuesList) . ";\n");
                        }
                    }
                    fwrite($handle, "\n");
                }
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fwrite($handle, "-- Selesai dumping database.\n");
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/sql');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'no-store, no-cache');

        return $response;
    }
}
