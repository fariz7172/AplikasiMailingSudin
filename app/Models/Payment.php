<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pptk_id',
        'vendor_id',
        'contract_id',
        'no_spd',
        'program',
        'kegiatan',
        'sub_kegiatan',
        'kode_rek',
        'no_spp',
        'no_spm',
        'tgl_spm',
        'no_sp2d',
        'tgl_sp2d',
        'nomor_bast',
        'tgl_bast',
        'no_kwi',
        'jumlah',
        'terbilang',
        'terbilang_kontrak',
        'tagihan_1',
        'tagihan_2',
        'tagihan_3',
        'tagihan_4',
        'tagihan_5',
        'keperluan',
        'denda',
        'progres',
        'nik',
        'jabatan',
    ];

    protected $casts = [
        'tgl_spm' => 'date',
        'tgl_sp2d' => 'date',
        'tgl_bast' => 'date',
    ];

    public function pptk()
    {
        return $this->belongsTo(Pptk::class, 'pptk_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}
