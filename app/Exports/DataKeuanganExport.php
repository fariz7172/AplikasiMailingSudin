<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataKeuanganExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Payment::with(['pptk', 'vendor', 'contract'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Pejabat PPTK',
            'Nama Perusahaan',
            'Nomor Kontrak',
            'Nilai Kontrak',
            'No. SPP',
            'No. SPM',
            'Tgl. SPM',
            'No. SP2D',
            'Tgl. SP2D',
            'Nomor BAST',
            'Progres',
            'Denda',
            'Keperluan'
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->pptk->nama ?? '-',
            $payment->vendor->nama_perusahaan ?? '-',
            $payment->contract->nomor_kontrak ?? '-',
            $payment->contract->nilai_kontrak ?? 0,
            $payment->no_spp,
            $payment->no_spm,
            $payment->tgl_spm ? $payment->tgl_spm->format('d-m-Y') : '-',
            $payment->no_sp2d,
            $payment->tgl_sp2d ? $payment->tgl_sp2d->format('d-m-Y') : '-',
            $payment->nomor_bast,
            $payment->progress,
            $payment->denda,
            $payment->keperluan,
        ];
    }
}
