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

    /**
     * Nilai SPK/Kontrak asli
     */
    public function getNilaiKontrakProgresAttribute()
    {
        return (float) ($this->jumlah ?: ($this->contract?->nilai_kontrak ?? 0));
    }

    /**
     * Ambil daftar tagihan bertahap (tagihan_1 s/d tagihan_5) yang bernilai > 0
     * @return array [ ['nomor' => 1, 'label' => 'Jumlah Tagihan 1', 'nilai' => 1000000], ... ]
     */
    public function getListTagihanAttribute()
    {
        $list = [];
        for ($i = 1; $i <= 5; $i++) {
            $val = $this->{"tagihan_$i"};
            if ($val !== null && (float) $val > 0) {
                $list[] = [
                    'nomor' => $i,
                    'label' => "Jumlah Tagihan $i",
                    'nilai' => (float) $val,
                ];
            }
        }
        return $list;
    }

    /**
     * Ambil nilai tagihan terakhir dari tagihan_1 s/d tagihan_5 jika ada yang terisi (> 0)
     */
    public function getTagihanTerakhirAttribute()
    {
        for ($i = 5; $i >= 1; $i--) {
            $val = $this->{"tagihan_$i"};
            if ($val !== null && (float) $val > 0) {
                return (float) $val;
            }
        }
        return null;
    }

    /**
     * Hitung/Ambil Jumlah Tagihan:
     * - Jika terdapat data tagihan bertahap (tagihan_1 s/d tagihan_5), ambil nilai dari tagihan TERAKHIR yang terisi.
     *   Tidak menjumlahkan tagihan dan tidak mengalikan dengan progres jika tagihan terisi.
     * - Jika tidak ada tagihan bertahap, baru dihitung berdasarkan persentase progres (%) dari nilai kontrak.
     * - Jika tidak ada progres, gunakan nilai kontrak penuh.
     */
    public function getJumlahTagihanProgresAttribute()
    {
        // 1. Ambil dari tagihan terakhir jika ada yang terisi (> 0)
        if ($this->tagihan_terakhir !== null) {
            return (float) $this->tagihan_terakhir;
        }

        $base = (float) ($this->jumlah ?: ($this->contract?->nilai_kontrak ?? 0));
        $progresRaw = trim($this->progres ?? '');
        $progresNum = (float) preg_replace('/[^0-9.]/', '', str_replace(',', '.', $progresRaw));

        // 2. Jika tidak ada tagihan bertahap tapi ada persentase progres (> 0), hitung proporsional
        if ($progresNum > 0) {
            return ($base * $progresNum) / 100;
        }

        return $base;
    }
}
