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
        // 1. PPTK (Index 35, 36, 37)
        $pptk = Pptk::firstOrCreate(
            ['nik' => $row[36] ?? '0'],
            [
                'nama' => $row[35] ?? '-',
                'jabatan' => $row[37] ?? '-'
            ]
        );

        // 2. Vendor (Index 5, 12, 13, 14, 15, 18, 19, 22, 23, 25, 57)
        $vendor = Vendor::firstOrCreate(
            ['nama_perusahaan' => $row[5] ?? '-'],
            [
                'direktur' => $row[12] ?? null,
                'npwp' => $row[13] ?? null,
                'akte' => $row[14] ?? null,
                'tgl_akte' => $row[15] ?? null,
                'tdp' => $row[18] ?? null,
                'tgl_tdp' => $row[19] ?? null,
                'bank' => $row[22] ?? null,
                'no_rekening' => $row[23] ?? null,
                'alamat' => $row[25] ?? null,
                'alamat_update' => $row[57] ?? null,
            ]
        );

        // 3. Contract (Index 6, 7, 44, 49, 50, 45, 52, 53, 46, 24, 21)
        $contract = Contract::firstOrCreate(
            ['nomor_kontrak' => $row[6] ?? '-'],
            [
                'tgl_kontrak' => $row[7] ?? null,
                'nilai_kontrak' => $this->parseMoney($row[44]),
                'addendum_kontrak' => $row[49] ?? null,
                'tgl_addendum' => $row[50] ?? null,
                'nilai_addendum1' => $this->parseMoney($row[45]),
                'addendum_kontrak2' => $row[52] ?? null,
                'tgl_addendum2' => $row[53] ?? null,
                'nilai_addendum2' => $this->parseMoney($row[46]),
                'jangka_waktu' => $row[24] ?? null,
                'tahun_tdp' => $row[21] ?? null,
            ]
        );

        // 4. Payment (Data Sisa)
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
            'tgl_spm' => $this->transformDate($row[27]),
            
            'no_sp2d' => $row[47] ?? null,
            'tgl_sp2d' => $this->transformDate($row[48]),

            'nomor_bast' => $row[31] ?? null,
            'tgl_bast' => $this->transformDate($row[32]),
            'no_kwi' => $row[34] ?? null,

            'jumlah' => $this->parseMoney($row[10]),
            'terbilang' => $row[11] ?? null,
            'tagihan_1' => $this->parseMoney($row[39]),
            'tagihan_2' => $this->parseMoney($row[40]),
            'tagihan_3' => $this->parseMoney($row[41]),
            'tagihan_4' => $this->parseMoney($row[42]),
            'tagihan_5' => $this->parseMoney($row[43]),

            'keperluan' => $row[33] ?? null,
            'denda' => $this->parseMoney($row[56]),
            'progress' => $row[30] ?? null,
        ]);
    }

    private function transformDate($value)
    {
        if (empty($value)) return null;
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        } catch (\Exception $e) {
            try {
                return Carbon::parse($value);
            } catch (\Exception $e2) {
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
