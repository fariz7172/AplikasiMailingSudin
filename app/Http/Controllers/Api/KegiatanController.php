<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Return kegiatans filtered by program_id.
     * GET /api/kegiatans?program_id=1
     */
    public function byProgram(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
        ]);

        $kegiatans = Kegiatan::where('program_id', $request->program_id)
            ->orderBy('nama')
            ->get(['id', 'nama', 'kode']);

        return response()->json($kegiatans);
    }

    /**
     * Return sub kegiatans filtered by kegiatan_id.
     * GET /api/sub-kegiatans?kegiatan_id=1
     */
    public function subByKegiatan(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
        ]);

        $subKegiatans = SubKegiatan::where('kegiatan_id', $request->kegiatan_id)
            ->orderBy('nama')
            ->get(['id', 'nama', 'kode', 'kode_rek']);

        return response()->json($subKegiatans);
    }

    /**
     * Store new Kegiatan via API
     * POST /api/kegiatans
     */
    public function storeKegiatan(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'kode' => 'nullable|string|max:255',
            'nama' => 'required|string|max:255',
        ]);

        $kegiatan = \App\Models\Kegiatan::create([
            'program_id' => $request->program_id,
            'kode' => $request->kode,
            'nama' => $request->nama,
            'keterangan' => 'Ditambahkan secara cepat melalui form pembayaran.',
        ]);

        return response()->json($kegiatan, 201);
    }

    /**
     * Store new Sub Kegiatan via API
     * POST /api/sub-kegiatans
     */
    public function storeSubKegiatan(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'kode' => 'nullable|string|max:255',
            'nama' => 'required|string|max:255',
        ]);

        $subKegiatan = SubKegiatan::create([
            'kegiatan_id' => $request->kegiatan_id,
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kode_rek' => null, // Optional if user doesn't provide
            'keterangan' => 'Ditambahkan secara cepat melalui form pembayaran.',
        ]);

        return response()->json($subKegiatan, 201);
    }
}
