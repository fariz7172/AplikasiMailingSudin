<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pptk;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Pptk::create([
            'nama' => 'AHMAD RIFAI, S.T.',
            'nik' => '198501012010011001',
            'jabatan' => 'Pejabat Pelaksana Teknis Kegiatan'
        ]);
    }
}
