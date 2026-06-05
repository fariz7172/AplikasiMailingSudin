<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'kode',
        'nama',
        'sub_kegiatan',
        'kode_rek',
        'keterangan',
    ];

    /**
     * Relasi ke Program (many-to-one)
     */
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /**
     * Relasi ke Payment (one-to-many)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'kegiatan_id');
    }

    /**
     * Relasi ke Sub Kegiatan (one-to-many)
     */
    public function subKegiatans()
    {
        return $this->hasMany(SubKegiatan::class, 'kegiatan_id');
    }
}
