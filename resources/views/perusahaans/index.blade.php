@extends('layouts.admin')

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
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Direktur</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Telepon</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($perusahaans as $p)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $p->nama_perusahaan }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $p->nama_direktur }}</td>
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
