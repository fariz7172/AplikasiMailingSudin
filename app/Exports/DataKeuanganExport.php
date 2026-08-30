<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DataKeuanganExport extends DefaultValueBinder implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithCustomValueBinder,
    WithStyles,
    ShouldAutoSize,
    WithColumnFormatting,
    WithEvents
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
            $payment->no_spd, // 0 (A)
            $payment->program, // 1 (B)
            $payment->kegiatan, // 2 (C)
            $payment->sub_kegiatan, // 3 (D)
            $payment->kode_rek, // 4 (E)
            $v->nama_perusahaan ?? null, // 5 (F)
            
            $c->nomor_kontrak ?? null, // 6 (G)
            $c && $c->tgl_kontrak ? $c->tgl_kontrak->format('d') : null, // 7 (H)
            $c && $c->tgl_kontrak ? $c->tgl_kontrak->format('m') : null, // 8 (I)
            $c && $c->tgl_kontrak ? $c->tgl_kontrak->format('Y') : null, // 9 (J)
            
            // Fix: Jumlah prioritaskan $payment->jumlah, fallback ke $c->nilai_kontrak
            $payment->jumlah !== null && $payment->jumlah !== '' ? (float) $payment->jumlah : ($c && $c->nilai_kontrak !== null && $c->nilai_kontrak !== '' ? (float) $c->nilai_kontrak : null), // 10 (K)
            
            // Fix: Terbilang prioritaskan $payment->terbilang, fallback ke $c->terbilang_kontrak
            $payment->terbilang ?: ($c->terbilang_kontrak ?? null), // 11 (L)
            
            $v->direktur ?? null, // 12 (M)
            $v->npwp ?? null, // 13 (N)
            $v->akte ?? null, // 14 (O)
            $v && $v->tgl_akte ? \Carbon\Carbon::parse($v->tgl_akte)->format('d') : null, // 15 (P)
            $v && $v->tgl_akte ? \Carbon\Carbon::parse($v->tgl_akte)->format('m') : null, // 16 (Q)
            $v && $v->tgl_akte ? \Carbon\Carbon::parse($v->tgl_akte)->format('Y') : null, // 17 (R)
            
            $v->tdp ?? null, // 18 (S)
            $v && $v->tgl_tdp ? \Carbon\Carbon::parse($v->tgl_tdp)->format('d') : null, // 19 (T)
            $v && $v->tgl_tdp ? \Carbon\Carbon::parse($v->tgl_tdp)->format('m') : null, // 20 (U)
            $v && $v->tgl_tdp ? \Carbon\Carbon::parse($v->tgl_tdp)->format('Y') : null, // 21 (V)
            
            $v->bank ?? null, // 22 (W)
            $v->no_rekening ?? null, // 23 (X)
            $c->jangka_waktu ?? null, // 24 (Y)
            $v->alamat ?? null, // 25 (Z)
            
            $payment->no_spm, // 26 (AA)
            $payment->tgl_spm ? $payment->tgl_spm->format('d') : null, // 27 (AB)
            $payment->tgl_spm ? $payment->tgl_spm->format('m') : null, // 28 (AC)
            $payment->tgl_spm ? $payment->tgl_spm->format('Y') : null, // 29 (AD)
            
            $payment->progres ?? $payment->progress, // 30 (AE)
            $payment->no_bast, // 31 (AF)
            $payment->tgl_bast ? $payment->tgl_bast->format('Y-m-d') : null, // 32 (AG)
            $payment->keperluan, // 33 (AH)
            $payment->no_kwi, // 34 (AI)
            
            $p->nama ?? null, // 35 (AJ)
            $payment->nik, // 36 (AK)
            $payment->jabatan, // 37 (AL)
            $payment->no_spp, // 38 (AM)
            
            $payment->tagihan_1 !== null && $payment->tagihan_1 !== '' ? (float) $payment->tagihan_1 : null, // 39 (AN)
            $payment->tagihan_2 !== null && $payment->tagihan_2 !== '' ? (float) $payment->tagihan_2 : null, // 40 (AO)
            $payment->tagihan_3 !== null && $payment->tagihan_3 !== '' ? (float) $payment->tagihan_3 : null, // 41 (AP)
            $payment->tagihan_4 !== null && $payment->tagihan_4 !== '' ? (float) $payment->tagihan_4 : null, // 42 (AQ)
            $payment->tagihan_5 !== null && $payment->tagihan_5 !== '' ? (float) $payment->tagihan_5 : null, // 43 (AR)
            
            // Fix: Nilai Kontrak prioritaskan $c->nilai_kontrak, fallback ke $payment->jumlah
            $c && $c->nilai_kontrak !== null && $c->nilai_kontrak !== '' ? (float) $c->nilai_kontrak : ($payment->jumlah !== null && $payment->jumlah !== '' ? (float) $payment->jumlah : null), // 44 (AS)
            $c && $c->nilai_addendum1 !== null && $c->nilai_addendum1 !== '' ? (float) $c->nilai_addendum1 : null, // 45 (AT)
            $c && $c->nilai_addendum2 !== null && $c->nilai_addendum2 !== '' ? (float) $c->nilai_addendum2 : null, // 46 (AU)
            
            $payment->no_sp2d, // 47 (AV)
            $payment->tgl_sp2d ? $payment->tgl_sp2d->format('Y-m-d') : null, // 48 (AW)
            
            $c->addendum_kontrak ?? null, // 49 (AX)
            $c && $c->tgl_addendum ? $c->tgl_addendum->format('d') : null, // 50 (AY)
            $c && $c->tgl_addendum ? $c->tgl_addendum->format('m') : null, // 51 (AZ)
            $c && $c->tgl_addendum ? $c->tgl_addendum->format('Y') : null, // 52 (BA)
            
            $c->addendum_kontrak2 ?? null, // 53 (BB)
            $c && $c->tgl_addendum2 ? $c->tgl_addendum2->format('d') : null, // 54 (BC)
            $c && $c->tgl_addendum2 ? $c->tgl_addendum2->format('m') : null, // 55 (BD)
            $c && $c->tgl_addendum2 ? $c->tgl_addendum2->format('Y') : null, // 56 (BE)
            
            $payment->denda !== null && $payment->denda !== '' ? (float) $payment->denda : null, // 57 (BF)
            $v->alamat_update ?? null, // 58 (BG)
        ];
    }

    public function columnFormats(): array
    {
        return [
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AN' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AO' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AP' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AQ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AR' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AS' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AT' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AU' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'BF' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling Header Row
        $sheet->getRowDimension(1)->setRowHeight(32);
        
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                    'name' => 'Calibri',
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0F172A'], // Deep Emerald / Slate Navy
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                // Freeze pane pada baris pertama (header)
                $sheet->freezePane('A2');

                // AutoFilter untuk seluruh kolom di baris header
                $sheet->setAutoFilter("A1:{$highestColumn}1");

                // Style data rows
                if ($highestRow >= 2) {
                    // Border tipis untuk seluruh cell data
                    $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'CBD5E1'],
                            ],
                        ],
                    ]);

                    // Alignment vertical center dan row height
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $sheet->getRowDimension($row)->setRowHeight(22);
                        
                        // Zebra Striping (baris genap sedikit keabuan/slate terang)
                        if ($row % 2 === 0) {
                            $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setRGB('F8FAFC');
                        }
                    }
                }
            },
        ];
    }
}
