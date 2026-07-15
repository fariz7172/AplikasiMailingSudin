<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan',
        'nama_direktur',
        'no_tlp',
        'alamat',
        'npwp',
        'akte',
        'tgl_akte',
        'tdp',
        'tgl_tdp'
    ];
}
