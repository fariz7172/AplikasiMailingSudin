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
        'perusahaan_id',
        'contract_id',
        'program_id',
        'kegiatan_id',
        'sub_kegiatan_id',
        'no_spd',
        'tgl_spd',
        'program',
        'kegiatan',
        'sub_kegiatan',
        'kode_rek',
        'no_spp',
        'tgl_spp',
        'no_spm',
        'tgl_spm',
        'no_sp2d',
        'tgl_sp2d',
        'no_bast',
        'tgl_bast',
        'no_kwi',
        'tgl_kwi',
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
        'print_data',
    ];

    protected $casts = [
        'tgl_spm' => 'date',
        'tgl_sp2d' => 'date',
        'tgl_bast' => 'date',
        'tgl_spd' => 'date',
        'tgl_spp' => 'date',
        'tgl_kwi' => 'date',
        'print_data' => 'array',
    ];

    public function pptk()
    {
        return $this->belongsTo(Pptk::class, 'pptk_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function programRef()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function kegiatanRef()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function subKegiatanRef()
    {
        return $this->belongsTo(SubKegiatan::class, 'sub_kegiatan_id');
    }
}
