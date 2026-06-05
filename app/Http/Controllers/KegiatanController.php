<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Program;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::orderBy('nama')->get();
        $query = Kegiatan::with('program');

        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        $kegiatans = $query->latest()->paginate(15)->withQueryString();
        return view('kegiatans.index', compact('kegiatans', 'programs'));
    }

    public function create(Request $request)
    {
        $programs = Program::orderBy('nama')->get();
        $selectedProgramId = $request->program_id;
        return view('kegiatans.create', compact('programs', 'selectedProgramId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id'    => 'required|exists:programs,id',
            'nama'          => 'required|string|max:255',
            'kode'          => 'nullable|string|max:50',
            'sub_kegiatan'  => 'nullable|string|max:255',
            'kode_rek'      => 'nullable|string|max:100',
            'keterangan'    => 'nullable|string',
        ]);

        Kegiatan::create($request->only([
            'program_id', 'kode', 'nama', 'sub_kegiatan', 'kode_rek', 'keterangan'
        ]));

        return redirect()->route('kegiatans.index')
            ->with('success', 'Data Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        return redirect()->route('kegiatans.edit', $kegiatan);
    }

    public function edit(Kegiatan $kegiatan)
    {
        $kegiatan->load('subKegiatans');
        $programs = Program::orderBy('nama')->get();
        return view('kegiatans.edit', compact('kegiatan', 'programs'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'program_id'    => 'required|exists:programs,id',
            'nama'          => 'required|string|max:255',
            'kode'          => 'nullable|string|max:50',
            'sub_kegiatan'  => 'nullable|string|max:255',
            'kode_rek'      => 'nullable|string|max:100',
            'keterangan'    => 'nullable|string',
        ]);

        $kegiatan->update($request->only([
            'program_id', 'kode', 'nama', 'sub_kegiatan', 'kode_rek', 'keterangan'
        ]));

        return redirect()->route('kegiatans.index')
            ->with('success', 'Data Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('kegiatans.index')
            ->with('success', 'Data Kegiatan berhasil dihapus.');
    }
}
