<?php

namespace App\Imports;

use App\Models\Pptk;
use App\Models\Vendor;
use App\Models\Contract;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class DataKeuanganImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2; // Mulai baca dari baris kedua (lewati header)
    }

    public function model(array $row)
    {
        // Cek apakah baris ini kosong (menghindari ghost rows dari Excel)
        $isEmpty = true;
        foreach ($row as $cell) {
            if ($cell !== null && trim((string)$cell) !== '') {
                $isEmpty = false;
                break;
            }
        }
        if ($isEmpty) {
            return null; // Skip baris ini
        }

        // 1. PPTK
        $pptk = Pptk::firstOrCreate([
            'nik' => $row[36] ?? '0',
            'nama' => $row[35] ?? '-',
            'jabatan' => $row[37] ?? '-'
        ]);

        // 2. Vendor
        $vendor = Vendor::firstOrCreate([
            'nama_perusahaan' => $row[5] ?? '-',
            'direktur' => $row[12] ?? null,
            'npwp' => $row[13] ?? null,
            'akte' => $row[14] ?? null,
            'tgl_akte' => $this->combineDate($row[17] ?? null, $row[16] ?? null, $row[15] ?? null),
            'tdp' => $row[18] ?? null,
            'tgl_tdp' => $this->combineDate($row[21] ?? null, $row[20] ?? null, $row[19] ?? null),
            'bank' => $row[22] ?? null,
            'no_rekening' => $row[23] ?? null,
            'alamat' => $row[25] ?? null,
            'alamat_update' => $row[58] ?? null,
        ]);

        // 3. Contract
        $contract = Contract::updateOrCreate(
            ['nomor_kontrak' => $row[6] ?? '-'],
            [
                'tgl_kontrak' => $this->combineDate($row[9] ?? null, $row[8] ?? null, $row[7] ?? null),
                'nilai_kontrak' => $this->parseMoney($row[44] ?? $row[10]),
                'terbilang_kontrak' => $row[11] ?? null,
                'addendum_kontrak' => $row[49] ?? null,
                'tgl_addendum' => $this->combineDate($row[52] ?? null, $row[51] ?? null, $row[50] ?? null),
                'nilai_addendum1' => $this->parseMoney($row[45]),
                'addendum_kontrak2' => $row[53] ?? null,
                'tgl_addendum2' => $this->combineDate($row[56] ?? null, $row[55] ?? null, $row[54] ?? null),
                'nilai_addendum2' => $this->parseMoney($row[46]),
                'jangka_waktu' => $row[24] ?? null,
            ]
        );

        // 4. Payment
        return new Payment([
            'pptk_id' => $pptk->id,
            'vendor_id' => $vendor->id,
            'contract_id' => $contract->id,
            
            'no_spd' => $row[0] ?? null,
            'program' => $row[1] ?? null,
            'kegiatan' => $row[2] ?? null,
            'sub_kegiatan' => $row[3] ?? null,
            'kode_rek' => $row[4] ?? null,

            'no_spp' => $row[38] ?? null,
            'no_spm' => $row[26] ?? null,
            'tgl_spm' => $this->combineDate($row[29] ?? null, $row[28] ?? null, $row[27] ?? null),
            
            'no_sp2d' => $row[47] ?? null,
            'tgl_sp2d' => $this->transformDate($row[48] ?? null),

            'no_bast' => $row[31] ?? null,
            'tgl_bast' => $this->transformDate($row[32] ?? null),
            'no_kwi' => $row[34] ?? null,

            'jumlah' => $this->parseMoney($row[10]),
            'terbilang' => $row[11] ?? null,
            'tagihan_1' => $this->parseMoney($row[39]),
            'tagihan_2' => $this->parseMoney($row[40]),
            'tagihan_3' => $this->parseMoney($row[41]),
            'tagihan_4' => $this->parseMoney($row[42]),
            'tagihan_5' => $this->parseMoney($row[43]),

            'keperluan' => $row[33] ?? null,
            'denda' => $this->parseMoney($row[57]),
            'progres' => $row[30] ?? null,
            'nik' => $row[36] ?? null,
            'jabatan' => $row[37] ?? null,
        ]);
    }

    private function combineDate($year, $month, $day)
    {
        if (empty($year) || empty($month) || empty($day)) return null;
        try {
            return Carbon::createFromFormat('Y-n-j', $year . '-' . $month . '-' . $day)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function transformDate($value)
    {
        if (empty($value)) return null;
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        } catch (\Throwable $e) {
            try {
                return Carbon::parse($value);
            } catch (\Throwable $e2) {
                return null;
            }
        }
    }

    private function parseMoney($value)
    {
        if (empty($value)) return 0;
        return (int) preg_replace('/[^0-9]/', '', $value);
    }
}
