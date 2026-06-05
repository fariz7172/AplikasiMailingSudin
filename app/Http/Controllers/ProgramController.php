<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::withCount('kegiatans')->latest()->paginate(15);
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        return view('programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'kode'            => 'nullable|string|max:50',
            'tahun_anggaran'  => 'nullable|digits:4|integer|min:2000|max:2099',
            'keterangan'      => 'nullable|string',
        ]);

        Program::create($request->only(['kode', 'nama', 'tahun_anggaran', 'keterangan']));

        return redirect()->route('programs.index')
            ->with('success', 'Data Program berhasil ditambahkan.');
    }

    public function show(Program $program)
    {
        return redirect()->route('programs.edit', $program);
    }

    public function edit(Program $program)
    {
        $program->load('kegiatans');
        return view('programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'kode'            => 'nullable|string|max:50',
            'tahun_anggaran'  => 'nullable|digits:4|integer|min:2000|max:2099',
            'keterangan'      => 'nullable|string',
        ]);

        $program->update($request->only(['kode', 'nama', 'tahun_anggaran', 'keterangan']));

        return redirect()->route('programs.index')
            ->with('success', 'Data Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('programs.index')
            ->with('success', 'Data Program berhasil dihapus.');
    }
}
