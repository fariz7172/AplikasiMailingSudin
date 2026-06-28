<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateImportExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'No_SPD', // 0
            'Program', // 1
            'Kegiatan', // 2
            'Sub kegiatan', // 3
            'Kode_Rek', // 4
            'NAMA PERUSHAAN', // 5
            'No_Kontrak', // 6
            'Tgl_Kontrak', // 7
            'Bln_Kontrak', // 8
            'Thn_Kontrak', // 9
            'Jumlah', // 10
            'Terbilang', // 11
            'Direktur', // 12
            'NPWP', // 13
            'Akte', // 14
            'Tgl. Akte', // 15
            'Bulan_Akte', // 16
            'Thn_akte', // 17
            'TDP', // 18
            'Tgl. TDP', // 19
            'Bulan_TDP', // 20
            'Tahun_TDP', // 21
            'Bank', // 22
            'Rek_Bank', // 23
            'Jangka Waktu', // 24
            'Alamat', // 25
            'No_SPM', // 26
            'Tgl_SPM', // 27
            'Bln_SPM', // 28
            'Thn_SPM', // 29
            'Progress', // 30
            'Nomor BAST', // 31
            'Tanggal BAST', // 32
            'Keperluan', // 33
            'No_Kwi', // 34
            'PPTK', // 35
            'NIK', // 36
            'JABATAN', // 37
            'No_SPP', // 38
            'Tagihan_1', // 39
            'Tagihan_2', // 40
            'Tagihan_3', // 41
            'Tagihan_4', // 42
            'Tagihan_5', // 43
            'Nilai Kontrak', // 44
            'Nilai_Addendum1', // 45
            'Nilai_Addendum2', // 46
            'No. SP2D', // 47
            'Tgl. SP2D', // 48
            'Adendum_Kontrak', // 49
            'Tgl_Addendum', // 50
            'Bln_Addendum', // 51
            'Thn_Addendum', // 52
            'Addendum_Kontrak2', // 53
            'Tgl_Addendum2', // 54
            'Bln_Addendum2', // 55
            'Thn_Addendum2', // 56
            'Denda', // 57
            'Alamat update' // 58
        ];
    }
}
