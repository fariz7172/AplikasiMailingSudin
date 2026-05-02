@extends('layouts.admin')

@section('title', 'Daftar Pejabat')
@section('page_title', 'Manajemen Data Pejabat (PPTK)')

@section('content')
<div class="space-y-6">
    <!-- Header & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Daftar Pejabat Aktif</h3>
            <p class="text-slate-500 text-sm">Kelola data Pejabat Pelaksana Teknis Kegiatan (PPTK)</p>
        </div>
        <a href="{{ route('pptk.create') }}" class="flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Tambah Pejabat
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl flex items-center gap-3 mb-6">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Pejabat</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">NIP / NIK</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No. Rekening</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pptk as $p)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-100 text-primary rounded-xl flex items-center justify-center font-bold">
                                    {{ substr($p->nama, 0, 1) }}
                                </div>
                                <span class="font-bold text-slate-700">{{ $p->nama }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">
                            <div class="space-y-1">
                                <p class="text-xs text-slate-400">NIP: {{ $p->nip ?? '-' }}</p>
                                <p class="text-xs text-slate-400">NIK: {{ $p->nik ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">
                                {{ $p->jabatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-mono text-sm italic">
                            {{ $p->no_rekening ?? 'Belum Diatur' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('pptk.edit', $p) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('pptk.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus data pejabat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <i data-lucide="users" class="w-12 h-12 stroke-[1]"></i>
                                <p>Belum ada data pejabat.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
