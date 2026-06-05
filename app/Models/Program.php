<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'tahun_anggaran',
        'keterangan',
    ];

    /**
     * Relasi ke Kegiatan (one-to-many)
     */
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'program_id');
    }

    /**
     * Relasi ke Payment (one-to-many)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'program_id');
    }
}
