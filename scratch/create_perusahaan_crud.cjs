const fs = require('fs');
const path = require('path');

const viewsDir = path.join(__dirname, '..', 'resources', 'views');
const perusahaanDir = path.join(viewsDir, 'perusahaans');
if (!fs.existsSync(perusahaanDir)) fs.mkdirSync(perusahaanDir, { recursive: true });

const indexBlade = `@extends('layouts.admin')

@section('title', 'Data Perusahaan')
@section('page_title', 'Data Perusahaan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Daftar Perusahaan</h2>
    <a href="{{ route('perusahaans.create') }}" class="px-4 py-2 bg-primary text-white rounded-xl font-bold hover:bg-primary/90 transition-colors">
        + Tambah Perusahaan
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Perusahaan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Telepon</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($perusahaans as $p)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $p->nama_perusahaan }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $p->no_tlp }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $p->alamat }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('perusahaans.edit', $p->id) }}" class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold hover:bg-amber-200">Edit</a>
                        <form action="{{ route('perusahaans.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold hover:bg-rose-200">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
`;
fs.writeFileSync(path.join(perusahaanDir, 'index.blade.php'), indexBlade);

const formFields = `
<div class="grid grid-cols-1 gap-6">
    <div>
        <label class="form-label-premium">Nama Perusahaan</label>
        <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan ?? '') }}" class="form-input-premium" required>
    </div>
    <div>
        <label class="form-label-premium">No. Telepon</label>
        <input type="text" name="no_tlp" value="{{ old('no_tlp', $perusahaan->no_tlp ?? '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">Alamat</label>
        <textarea name="alamat" rows="3" class="form-input-premium">{{ old('alamat', $perusahaan->alamat ?? '') }}</textarea>
    </div>
</div>
`;

const createBlade = `@extends('layouts.admin')
@section('title', 'Tambah Perusahaan')
@section('page_title', 'Tambah Perusahaan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('perusahaans.store') }}" method="POST">
        @csrf
        ${formFields}
        <div class="mt-8 flex justify-end">
            <a href="{{ route('perusahaans.index') }}" class="px-6 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold mr-4">Batal</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-xl font-bold">Simpan</button>
        </div>
    </form>
</div>
@endsection
`;
fs.writeFileSync(path.join(perusahaanDir, 'create.blade.php'), createBlade);

const editBlade = `@extends('layouts.admin')
@section('title', 'Edit Perusahaan')
@section('page_title', 'Edit Perusahaan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('perusahaans.update', $perusahaan->id) }}" method="POST">
        @csrf
        @method('PUT')
        ${formFields}
        <div class="mt-8 flex justify-end">
            <a href="{{ route('perusahaans.index') }}" class="px-6 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold mr-4">Batal</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-xl font-bold">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
`;
fs.writeFileSync(path.join(perusahaanDir, 'edit.blade.php'), editBlade);

console.log('CRUD Views for Perusahaans generated.');
