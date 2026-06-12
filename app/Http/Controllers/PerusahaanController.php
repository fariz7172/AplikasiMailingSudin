<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perusahaan;

class PerusahaanController extends Controller
{
    public function index()
    {
        $perusahaans = Perusahaan::latest()->get();
        return view('perusahaans.index', compact('perusahaans'));
    }

    public function create()
    {
        return view('perusahaans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'no_tlp' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
        ]);
        Perusahaan::create($request->all());
        return redirect()->route('perusahaans.index')->with('success', 'Data Perusahaan berhasil ditambahkan.');
    }

    public function edit(Perusahaan $perusahaan)
    {
        return view('perusahaans.edit', compact('perusahaan'));
    }

    public function update(Request $request, Perusahaan $perusahaan)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'no_tlp' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
        ]);
        $perusahaan->update($request->all());
        return redirect()->route('perusahaans.index')->with('success', 'Data Perusahaan berhasil diperbarui.');
    }

    public function destroy(Perusahaan $perusahaan)
    {
        $perusahaan->delete();
        return redirect()->route('perusahaans.index')->with('success', 'Data Perusahaan berhasil dihapus.');
    }
}
