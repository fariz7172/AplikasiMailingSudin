<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_kontrak',
        'tgl_kontrak',
        'nilai_kontrak',
        'addendum_kontrak',
        'tgl_addendum',
        'nilai_addendum1',
        'addendum_kontrak2',
        'tgl_addendum2',
        'nilai_addendum2',
        'jangka_waktu',
        'tahun_tdp',
    ];

    protected $casts = [
        'tgl_kontrak' => 'date',
        'tgl_addendum' => 'date',
        'tgl_addendum2' => 'date',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'contract_id');
    }
}
