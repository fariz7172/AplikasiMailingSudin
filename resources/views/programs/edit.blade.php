@extends('layouts.admin')

@section('title', 'Edit Program')
@section('page_title', 'Edit Master Program')

@section('content')
<div class="space-y-6">

    <div class="max-w-2xl">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            {{-- Header --}}
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center">
                    <i data-lucide="folder-edit" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-800">Edit Program</h2>
                    <p class="text-xs text-slate-400">Perbarui data program: <span class="font-bold text-slate-600">{{ $program->nama }}</span></p>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('programs.update', $program->id) }}" method="POST" class="p-8 space-y-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label-premium">Kode Program</label>
                        <input type="text" name="kode" value="{{ old('kode', $program->kode) }}"
                            placeholder="Contoh: 1.03.02"
                            class="form-input-premium @error('kode') border-red-400 @enderror">
                        @error('kode')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label-premium">Tahun Anggaran</label>
                        <input type="number" name="tahun_anggaran" value="{{ old('tahun_anggaran', $program->tahun_anggaran) }}"
                            placeholder="Contoh: 2026" min="2000" max="2099"
                            class="form-input-premium @error('tahun_anggaran') border-red-400 @enderror">
                        @error('tahun_anggaran')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="form-label-premium">Nama Program <span class="text-red-400">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $program->nama) }}"
                        class="form-input-premium @error('nama') border-red-400 @enderror">
                    @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label-premium">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                        class="form-input-premium resize-none @error('keterangan') border-red-400 @enderror">{{ old('keterangan', $program->keterangan) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="px-8 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:bg-indigo-700 transition-all flex items-center gap-2 text-sm">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Perbarui Program
                    </button>
                    <a href="{{ route('programs.index') }}"
                        class="px-6 py-3 text-slate-500 font-bold hover:bg-slate-100 rounded-2xl transition-all text-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Kegiatan dalam Program ini --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="layers" class="w-5 h-5 text-accent"></i>
                <h3 class="font-black text-slate-800">Kegiatan dalam Program Ini</h3>
                <span class="px-2 py-0.5 bg-accent/10 text-accent text-xs font-bold rounded-full">
                    {{ $program->kegiatans->count() }}
                </span>
            </div>
            <a href="{{ route('kegiatans.create', ['program_id' => $program->id]) }}"
                class="px-4 py-2 bg-accent text-white text-xs font-bold rounded-xl hover:bg-sky-600 transition-all flex items-center gap-2">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                Tambah Kegiatan
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Kegiatan</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Sub Kegiatan</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode Rekening</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($program->kegiatans as $kegiatan)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-3">
                            <span class="text-xs font-mono text-slate-500">{{ $kegiatan->kode ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-3 font-semibold text-slate-700">{{ $kegiatan->nama }}</td>
                        <td class="px-6 py-3 text-slate-500 text-xs">{{ $kegiatan->sub_kegiatan ?? '-' }}</td>
                        <td class="px-6 py-3 text-slate-500 text-xs font-mono">{{ $kegiatan->kode_rek ?? '-' }}</td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kegiatans.edit', $kegiatan->id) }}"
                                    class="p-1.5 hover:bg-blue-50 text-slate-400 hover:text-blue-600 rounded-lg transition-all">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                </a>
                                <form action="{{ route('kegiatans.destroy', $kegiatan->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus kegiatan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-lg transition-all">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-sm">
                            Belum ada kegiatan.
                            <a href="{{ route('kegiatans.create', ['program_id' => $program->id]) }}" class="text-primary font-bold hover:underline ml-1">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
