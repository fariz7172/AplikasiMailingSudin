<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateImportExport implements WithHeadings
{
    public function headings(): array
    {
        // Menyusun header sesuai dengan urutan index yang kita gunakan di DataKeuanganImport
        $headers = array_fill(0, 67, ''); // Inisialisasi 67 kolom kosong
        
        // Mengisi kolom-kolom kunci agar user tahu di mana harus menaruh datanya
        $headers[10] = 'NAMA REKANAN / VENDOR';
        $headers[15] = 'NOMOR KONTRAK';
        $headers[24] = 'TAHUN TDP';
        $headers[25] = 'BANK';
        $headers[26] = 'NOMOR REKENING';
        $headers[27] = 'JANGKA WAKTU';
        $headers[28] = 'ALAMAT';
        $headers[29] = 'NOMOR SPM';
        $headers[30] = 'TANGGAL SPM (Y-M-D)';
        $headers[33] = 'PROGRESS';
        $headers[35] = 'NOMOR BAST';
        $headers[36] = 'TANGGAL BAST (Y-M-D)';
        $headers[37] = 'KEPERLUAN';
        $headers[39] = 'NAMA PPTK';
        $headers[40] = 'NIK PPTK';
        $headers[41] = 'JABATAN PPTK';
        $headers[42] = 'NOMOR SPP';
        $headers[48] = 'NILAI KONTRAK';
        $headers[49] = 'NILAI ADDENDUM 1';
        $headers[50] = 'NILAI ADDENDUM 2';
        $headers[51] = 'NOMOR SP2D';
        $headers[52] = 'TANGGAL SP2D (Y-M-D)';
        $headers[61] = 'DENDA';

        return $headers;
    }
}
