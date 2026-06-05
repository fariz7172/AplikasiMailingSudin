<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    use HasFactory;

    protected $table = 'sub_kegiatans';

    protected $fillable = [
        'kegiatan_id',
        'kode',
        'nama',
        'kode_rek',
        'keterangan',
    ];

    /**
     * Relasi ke Kegiatan (many-to-one)
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    /**
     * Relasi ke Payment (one-to-many)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'sub_kegiatan_id');
    }
}
