@extends('layouts.admin')

@section('title', 'Master Program')
@section('page_title', 'Daftar Master Program')

@section('content')
<div class="space-y-6">

    {{-- Alert --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        class="flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-semibold">
        <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Action Bar --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <p class="text-sm text-slate-500">Kelola data Program sebagai kategori induk kegiatan anggaran.</p>
        </div>
        <a href="{{ route('programs.create') }}"
            class="flex-shrink-0 px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/30 hover:bg-indigo-700 transition-all flex items-center gap-2 text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Program
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-12">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Program</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Tahun</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Jumlah Kegiatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($programs as $program)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-400 font-medium">
                            {{ $loop->iteration + ($programs->firstItem() - 1) }}
                        </td>
                        <td class="px-6 py-4">
                            @if($program->kode)
                            <span class="px-2 py-1 bg-primary/10 text-primary text-xs font-black rounded-lg">{{ $program->kode }}</span>
                            @else
                            <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800">{{ $program->nama }}</span>
                            @if($program->keterangan)
                            <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs">{{ $program->keterangan }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-lg">
                                {{ $program->tahun_anggaran ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('kegiatans.index', ['program_id' => $program->id]) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-accent/10 text-accent text-xs font-bold rounded-full hover:bg-accent hover:text-white transition-all">
                                <i data-lucide="layers" class="w-3 h-3"></i>
                                {{ $program->kegiatans_count }} Kegiatan
                            </a>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kegiatans.create', ['program_id' => $program->id]) }}"
                                    class="p-2 hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 rounded-xl transition-all" title="Tambah Kegiatan">
                                    <i data-lucide="list-plus" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('programs.edit', $program->id) }}"
                                    class="p-2 hover:bg-blue-50 text-slate-400 hover:text-blue-600 rounded-xl transition-all" title="Edit Program">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('programs.destroy', $program->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus program ini? Semua kegiatan di dalamnya juga akan terhapus!')">
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
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <i data-lucide="folder-open" class="w-14 h-14 mb-4 opacity-20 text-primary"></i>
                                <p class="text-sm font-semibold">Belum ada data Program.</p>
                                <a href="{{ route('programs.create') }}" class="mt-4 text-primary font-bold text-xs hover:underline">Tambah Program Pertama</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($programs->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $programs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
