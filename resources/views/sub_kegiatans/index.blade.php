@extends('layouts.admin')

@section('title', 'Master Sub Kegiatan')
@section('page_title', 'Daftar Master Sub Kegiatan')

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        class="flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold">
        <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Breadcrumb Hierarki --}}
    <div class="flex items-center gap-2 text-xs text-slate-400 font-semibold">
        <a href="{{ route('programs.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
            <i data-lucide="folder" class="w-3.5 h-3.5"></i> Program
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="{{ route('kegiatans.index') }}" class="hover:text-accent transition-colors flex items-center gap-1">
            <i data-lucide="layers" class="w-3.5 h-3.5"></i> Kegiatan
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-700 flex items-center gap-1">
            <i data-lucide="git-branch" class="w-3.5 h-3.5"></i> Sub Kegiatan
        </span>
    </div>

    {{-- Filter & Action --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <form action="{{ route('sub-kegiatans.index') }}" method="GET"
            class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full md:w-auto">
            <select name="program_id" onchange="this.form.submit()"
                class="form-input-premium w-64 py-2.5 text-sm">
                <option value="">— Semua Program —</option>
                @foreach($programs as $prog)
                <option value="{{ $prog->id }}" {{ request('program_id') == $prog->id ? 'selected' : '' }}>
                    {{ $prog->nama }} {{ $prog->tahun_anggaran ? '('.$prog->tahun_anggaran.')' : '' }}
                </option>
                @endforeach
            </select>

            @if($kegiatans->isNotEmpty())
            <select name="kegiatan_id" onchange="this.form.submit()"
                class="form-input-premium w-64 py-2.5 text-sm">
                <option value="">— Semua Kegiatan —</option>
                @foreach($kegiatans as $keg)
                <option value="{{ $keg->id }}" {{ request('kegiatan_id') == $keg->id ? 'selected' : '' }}>
                    {{ $keg->nama }}
                </option>
                @endforeach
            </select>
            @endif
        </form>

        <a href="{{ route('sub-kegiatans.create', request()->only(['program_id', 'kegiatan_id'])) }}"
            class="flex-shrink-0 px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/30 hover:bg-indigo-700 transition-all flex items-center gap-2 text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Sub Kegiatan
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-10">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Program → Kegiatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Sub Kegiatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode Rekening</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($subKegiatans as $sub)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-400 font-medium">
                            {{ $loop->iteration + ($subKegiatans->firstItem() - 1) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] text-primary font-bold uppercase tracking-wider">
                                    {{ $sub->kegiatan->program->nama ?? '-' }}
                                </span>
                                <span class="text-xs font-semibold text-slate-700">
                                    {{ $sub->kegiatan->nama ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono text-slate-500">{{ $sub->kode ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">{{ $sub->nama }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono text-slate-500">{{ $sub->kode_rek ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('sub-kegiatans.edit', $sub->id) }}"
                                    class="p-2 hover:bg-blue-50 text-slate-400 hover:text-blue-600 rounded-xl transition-all" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('sub-kegiatans.destroy', $sub->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus sub kegiatan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-xl transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <i data-lucide="git-branch" class="w-14 h-14 mb-4 opacity-20 text-primary"></i>
                                <p class="text-sm font-semibold">Belum ada data Sub Kegiatan.</p>
                                <a href="{{ route('sub-kegiatans.create') }}"
                                    class="mt-4 text-primary font-bold text-xs hover:underline">Tambah Sub Kegiatan Pertama</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subKegiatans->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $subKegiatans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
