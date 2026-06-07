<?php

namespace App\Http\Controllers;

use App\Models\Pptk;
use Illuminate\Http\Request;

class SlipGajiController extends Controller
{
    /**
     * Menampilkan halaman daftar PPTK untuk dipilih.
     */
    public function index()
    {
        $pptks = Pptk::all();
        return view('slip_gaji.index', compact('pptks'));
    }

    /**
     * Menerima request cetak slip gaji dan merender tampilan cetak
     * untuk setiap PPTK yang dipilih.
     */
    public function print(Request $request)
    {
        $request->validate([
            'pptk_ids' => 'required|array',
            'pptk_ids.*' => 'exists:pptk,id',
            'tgl_cetak' => 'nullable|date',
        ]);

        $pptks = Pptk::whereIn('id', $request->pptk_ids)->get();
        
        // Gunakan tanggal cetak dari input, atau hari ini jika kosong
        $tgl_cetak = $request->tgl_cetak 
            ? \Carbon\Carbon::parse($request->tgl_cetak) 
            : now();

        return view('slip_gaji.print', compact('pptks', 'tgl_cetak'));
    }
}
