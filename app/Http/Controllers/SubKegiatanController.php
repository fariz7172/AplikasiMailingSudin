<?php

namespace App\Http\Controllers;

use App\Models\SubKegiatan;
use App\Models\Kegiatan;
use App\Models\Program;
use Illuminate\Http\Request;

class SubKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $programs  = Program::orderBy('nama')->get();
        $kegiatans = collect();
        $query     = SubKegiatan::with('kegiatan.program');

        if ($request->filled('program_id')) {
            $kegiatans = Kegiatan::where('program_id', $request->program_id)->orderBy('nama')->get();
        }

        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        } elseif ($request->filled('program_id')) {
            $query->whereHas('kegiatan', fn($q) => $q->where('program_id', $request->program_id));
        }

        $subKegiatans = $query->latest()->paginate(15)->withQueryString();

        return view('sub_kegiatans.index', compact('subKegiatans', 'programs', 'kegiatans'));
    }

    public function create(Request $request)
    {
        $programs         = Program::orderBy('nama')->get();
        $kegiatans        = collect();
        $selectedKegiatanId = $request->kegiatan_id;

        if ($request->filled('program_id')) {
            $kegiatans = Kegiatan::where('program_id', $request->program_id)->orderBy('nama')->get();
        } elseif ($selectedKegiatanId) {
            $kegiatan  = Kegiatan::find($selectedKegiatanId);
            $kegiatans = $kegiatan
                ? Kegiatan::where('program_id', $kegiatan->program_id)->orderBy('nama')->get()
                : collect();
        }

        return view('sub_kegiatans.create', compact('programs', 'kegiatans', 'selectedKegiatanId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'nama'        => 'required|string|max:255',
            'kode'        => 'nullable|string|max:50',
            'kode_rek'    => 'nullable|string|max:100',
            'keterangan'  => 'nullable|string',
        ]);

        SubKegiatan::create($request->only(['kegiatan_id', 'kode', 'nama', 'kode_rek', 'keterangan']));

        return redirect()->route('sub-kegiatans.index')
            ->with('success', 'Data Sub Kegiatan berhasil ditambahkan.');
    }

    public function show(SubKegiatan $subKegiatan)
    {
        return redirect()->route('sub-kegiatans.edit', $subKegiatan);
    }

    public function edit(SubKegiatan $subKegiatan)
    {
        $programs  = Program::orderBy('nama')->get();
        $kegiatans = Kegiatan::where('program_id', $subKegiatan->kegiatan->program_id)
            ->orderBy('nama')->get();

        return view('sub_kegiatans.edit', compact('subKegiatan', 'programs', 'kegiatans'));
    }

    public function update(Request $request, SubKegiatan $subKegiatan)
    {
        $request->validate([
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'nama'        => 'required|string|max:255',
            'kode'        => 'nullable|string|max:50',
            'kode_rek'    => 'nullable|string|max:100',
            'keterangan'  => 'nullable|string',
        ]);

        $subKegiatan->update($request->only(['kegiatan_id', 'kode', 'nama', 'kode_rek', 'keterangan']));

        return redirect()->route('sub-kegiatans.index')
            ->with('success', 'Data Sub Kegiatan berhasil diperbarui.');
    }

    public function destroy(SubKegiatan $subKegiatan)
    {
        $subKegiatan->delete();
        return redirect()->route('sub-kegiatans.index')
            ->with('success', 'Data Sub Kegiatan berhasil dihapus.');
    }
}
