<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class DataKeuanganExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithCustomValueBinder
{
    public function bindValue(Cell $cell, $value)
    {
        $column = $cell->getColumn();
        
        if ($value !== null && $value !== '' && (in_array($column, ['A', 'E', 'G', 'N', 'O', 'S', 'X', 'AA', 'AF', 'AI', 'AK', 'AM', 'AV', 'AX', 'BB']) || (is_string($value) && preg_match('/^[0-9\.\-\/\s]+$/', $value) && strlen(preg_replace('/[^0-9]/', '', $value)) >= 10))) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Payment::with(['pptk', 'vendor', 'contract']);

        if (request()->has('search') && request('search') != '') {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('vendor', function($v) use ($search) {
                    $v->where('nama_perusahaan', 'like', "%$search%");
                })->orWhereHas('contract', function($c) use ($search) {
                    $c->where('nomor_kontrak', 'like', "%$search%");
                })->orWhere('no_spm', 'like', "%$search%")
                  ->orWhere('no_sp2d', 'like', "%$search%")
                  ->orWhere('program', 'like', "%$search%");
            });
        }

        return $query->orderBy('no_spm', 'desc')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No_SPD', 'Program', 'Kegiatan', 'Sub kegiatan', 'Kode_Rek', 'NAMA PERUSHAAN',
            'No_Kontrak', 'Tgl_Kontrak', 'Bln_Kontrak', 'Thn_Kontrak', 'Jumlah', 'Terbilang',
            'Direktur', 'NPWP', 'Akte', 'Tgl. Akte', 'Bulan_Akte', 'Thn_akte',
            'TDP', 'Tgl. TDP', 'Bulan_TDP', 'Tahun_TDP', 'Bank', 'Rek_Bank',
            'Jangka Waktu', 'Alamat', 'No_SPM', 'Tgl_SPM', 'Bln_SPM', 'Thn_SPM',
            'Progress', 'Nomor BAST', 'Tanggal BAST', 'Keperluan', 'No_Kwi',
            'PPTK', 'NIK', 'JABATAN', 'No_SPP', 'Tagihan_1', 'Tagihan_2', 'Tagihan_3',
            'Tagihan_4', 'Tagihan_5', 'Nilai Kontrak', 'Nilai_Addendum1', 'Nilai_Addendum2',
            'No. SP2D', 'Tgl. SP2D', 'Adendum_Kontrak', 'Tgl_Addendum', 'Bln_Addendum', 'Thn_Addendum',
            'Addendum_Kontrak2', 'Tgl_Addendum2', 'Bln_Addendum2', 'Thn_Addendum2',
            'Denda', 'Alamat update'
        ];
    }

    public function map($payment): array
    {
        $c = $payment->contract;
        $v = $payment->vendor;
        $p = $payment->pptk;

        return [
            $payment->no_spd, // 0
            $payment->program, // 1
            $payment->kegiatan, // 2
            $payment->sub_kegiatan, // 3
            $payment->kode_rek, // 4
            $v->nama_perusahaan ?? null, // 5
            
            $c->nomor_kontrak ?? null, // 6
            $c && $c->tgl_kontrak ? $c->tgl_kontrak->format('d') : null, // 7
            $c && $c->tgl_kontrak ? $c->tgl_kontrak->format('m') : null, // 8
            $c && $c->tgl_kontrak ? $c->tgl_kontrak->format('Y') : null, // 9
            
            $c->nilai_kontrak ?? 0, // 10 (Jumlah di Excel = Nilai Kontrak)
            $c->terbilang_kontrak ?? null, // 11 (Terbilang di Excel = Terbilang Kontrak)
            
            $v->direktur ?? null, // 12
            $v->npwp ?? null, // 13
            $v->akte ?? null, // 14
            $v && $v->tgl_akte ? \Carbon\Carbon::parse($v->tgl_akte)->format('d') : null, // 15
            $v && $v->tgl_akte ? \Carbon\Carbon::parse($v->tgl_akte)->format('m') : null, // 16
            $v && $v->tgl_akte ? \Carbon\Carbon::parse($v->tgl_akte)->format('Y') : null, // 17
            
            $v->tdp ?? null, // 18
            $v && $v->tgl_tdp ? \Carbon\Carbon::parse($v->tgl_tdp)->format('d') : null, // 19
            $v && $v->tgl_tdp ? \Carbon\Carbon::parse($v->tgl_tdp)->format('m') : null, // 20
            $v && $v->tgl_tdp ? \Carbon\Carbon::parse($v->tgl_tdp)->format('Y') : null, // 21
            
            $v->bank ?? null, // 22
            $v->no_rekening ?? null, // 23
            $c->jangka_waktu ?? null, // 24
            $v->alamat ?? null, // 25
            
            $payment->no_spm, // 26
            $payment->tgl_spm ? $payment->tgl_spm->format('d') : null, // 27
            $payment->tgl_spm ? $payment->tgl_spm->format('m') : null, // 28
            $payment->tgl_spm ? $payment->tgl_spm->format('Y') : null, // 29
            
            $payment->progres ?? $payment->progress, // 30
            $payment->no_bast, // 31
            $payment->tgl_bast ? $payment->tgl_bast->format('Y-m-d') : null, // 32
            $payment->keperluan, // 33
            $payment->no_kwi, // 34
            
            $p->nama ?? null, // 35
            $payment->nik, // 36
            $payment->jabatan, // 37
            $payment->no_spp, // 38
            
            $payment->tagihan_1, // 39
            $payment->tagihan_2, // 40
            $payment->tagihan_3, // 41
            $payment->tagihan_4, // 42
            $payment->tagihan_5, // 43
            
            $c->nilai_kontrak ?? null, // 44
            $c->nilai_addendum1 ?? null, // 45
            $c->nilai_addendum2 ?? null, // 46
            
            $payment->no_sp2d, // 47
            $payment->tgl_sp2d ? $payment->tgl_sp2d->format('Y-m-d') : null, // 48
            
            $c->addendum_kontrak ?? null, // 49
            $c && $c->tgl_addendum ? $c->tgl_addendum->format('d') : null, // 50
            $c && $c->tgl_addendum ? $c->tgl_addendum->format('m') : null, // 51
            $c && $c->tgl_addendum ? $c->tgl_addendum->format('Y') : null, // 52
            
            $c->addendum_kontrak2 ?? null, // 53
            $c && $c->tgl_addendum2 ? $c->tgl_addendum2->format('d') : null, // 54
            $c && $c->tgl_addendum2 ? $c->tgl_addendum2->format('m') : null, // 55
            $c && $c->tgl_addendum2 ? $c->tgl_addendum2->format('Y') : null, // 56
            
            $payment->denda, // 57
            $v->alamat_update ?? null, // 58
        ];
    }
}
