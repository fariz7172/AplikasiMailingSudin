<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pptk extends Model
{
    use HasFactory;

    protected $table = 'pptk';

    protected $fillable = [
        'nama',
        'nip',
        'nik',
        'jabatan',
        'no_rekening',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'pptk_id');
    }
}
