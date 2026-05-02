<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan',
        'direktur',
        'npwp',
        'akte',
        'tgl_akte',
        'tdp',
        'tgl_tdp',
        'bank',
        'no_rekening',
        'alamat',
        'alamat_update',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'vendor_id');
    }
}
