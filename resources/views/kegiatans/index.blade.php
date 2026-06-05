@extends('layouts.admin')

@section('title', 'Master Kegiatan')
@section('page_title', 'Daftar Master Kegiatan')

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        class="flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold">
        <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filter & Action Bar --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        {{-- Filter by Program --}}
        <form action="{{ route('kegiatans.index') }}" method="GET" class="flex items-center gap-3">
            <label class="text-sm font-bold text-slate-500">Filter Program:</label>
            <select name="program_id" onchange="this.form.submit()"
                class="form-input-premium w-72 py-2.5">
                <option value="">— Semua Program —</option>
                @foreach($programs as $prog)
                <option value="{{ $prog->id }}" {{ request('program_id') == $prog->id ? 'selected' : '' }}>
                    {{ $prog->nama }} {{ $prog->tahun_anggaran ? '('.$prog->tahun_anggaran.')' : '' }}
                </option>
                @endforeach
            </select>
        </form>

        <a href="{{ route('kegiatans.create', request()->only('program_id')) }}"
            class="flex-shrink-0 px-6 py-3 bg-accent text-white font-bold rounded-2xl shadow-lg shadow-accent/30 hover:bg-sky-600 transition-all flex items-center gap-2 text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Kegiatan
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-12">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Program Induk</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Kegiatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Sub Kegiatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode Rek.</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($kegiatans as $kegiatan)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-400 font-medium">
                            {{ $loop->iteration + ($kegiatans->firstItem() - 1) }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('programs.edit', $kegiatan->program_id) }}"
                                class="text-xs font-bold text-primary hover:underline truncate max-w-[150px] block">
                                {{ $kegiatan->program->nama ?? '-' }}
                            </a>
                            @if($kegiatan->program?->tahun_anggaran)
                            <span class="text-[10px] text-slate-400">{{ $kegiatan->program->tahun_anggaran }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono text-slate-500">{{ $kegiatan->kode ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800">{{ $kegiatan->nama }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs max-w-[150px] truncate">
                            {{ $kegiatan->sub_kegiatan ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono text-slate-500">{{ $kegiatan->kode_rek ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kegiatans.edit', $kegiatan->id) }}"
                                    class="p-2 hover:bg-blue-50 text-slate-400 hover:text-blue-600 rounded-xl transition-all" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('kegiatans.destroy', $kegiatan->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus kegiatan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-xl transition-all" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <i data-lucide="layers" class="w-14 h-14 mb-4 opacity-20 text-accent"></i>
                                <p class="text-sm font-semibold">Belum ada data Kegiatan.</p>
                                @if(!$programs->isEmpty())
                                <a href="{{ route('kegiatans.create') }}" class="mt-4 text-primary font-bold text-xs hover:underline">Tambah Kegiatan Pertama</a>
                                @else
                                <p class="mt-2 text-xs">Silakan buat <a href="{{ route('programs.create') }}" class="text-primary font-bold hover:underline">Program</a> terlebih dahulu.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kegiatans->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $kegiatans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
