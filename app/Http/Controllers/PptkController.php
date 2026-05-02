<?php

namespace App\Http\Controllers;

use App\Models\Pptk;
use Illuminate\Http\Request;

class PptkController extends Controller
{
    public function index()
    {
        $pptk = Pptk::latest()->get();
        return view('pptk.index', compact('pptk'));
    }

    public function create()
    {
        return view('pptk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:255',
            'no_rekening' => 'nullable|string|max:50',
        ]);

        Pptk::create($request->all());

        return redirect()->route('pptk.index')->with('success', 'Data Pejabat berhasil ditambahkan.');
    }

    public function show(Pptk $pptk)
    {
        return redirect()->route('pptk.edit', $pptk);
    }

    public function edit(Pptk $pptk)
    {
        return view('pptk.edit', compact('pptk'));
    }

    public function update(Request $request, Pptk $pptk)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:255',
            'no_rekening' => 'nullable|string|max:50',
        ]);

        $pptk->update($request->all());

        return redirect()->route('pptk.index')->with('success', 'Data Pejabat berhasil diperbarui.');
    }

    public function destroy(Pptk $pptk)
    {
        $pptk->delete();
        return redirect()->route('pptk.index')->with('success', 'Data Pejabat berhasil dihapus.');
    }
}
