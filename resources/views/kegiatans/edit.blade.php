@extends('layouts.admin')

@section('title', 'Edit Kegiatan')
@section('page_title', 'Edit Master Kegiatan')

@section('content')
<div class="space-y-6">

    {{-- Form Edit Kegiatan --}}
    <div class="max-w-2xl">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center">
                    <i data-lucide="edit-3" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-800">Edit Kegiatan</h2>
                    <p class="text-xs text-slate-400">Perbarui: <span class="font-bold text-slate-600">{{ $kegiatan->nama }}</span></p>
                </div>
            </div>

            <form action="{{ route('kegiatans.update', $kegiatan->id) }}" method="POST" class="p-8 space-y-6">
                @csrf @method('PUT')

                <div>
                    <label class="form-label-premium">Program Induk <span class="text-red-400">*</span></label>
                    <select name="program_id" class="form-input-premium @error('program_id') border-red-400 @enderror">
                        <option value="">— Pilih Program —</option>
                        @foreach($programs as $prog)
                        <option value="{{ $prog->id }}" {{ old('program_id', $kegiatan->program_id) == $prog->id ? 'selected' : '' }}>
                           {{ $prog->kode }} - {{ $prog->nama }} {{ $prog->tahun_anggaran ? '('.$prog->tahun_anggaran.')' : '' }}
                        </option>
                        @endforeach
                    </select>
                    @error('program_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label-premium">Kode Kegiatan</label>
                        <input type="text" name="kode" value="{{ old('kode', $kegiatan->kode) }}"
                            class="form-input-premium @error('kode') border-red-400 @enderror">
                        @error('kode')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label-premium">Kode Rekening <span class="text-slate-300 font-normal normal-case text-[10px]">(level kegiatan)</span></label>
                        <input type="text" name="kode_rek" value="{{ old('kode_rek', $kegiatan->kode_rek) }}"
                            class="form-input-premium @error('kode_rek') border-red-400 @enderror">
                        @error('kode_rek')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="form-label-premium">Nama Kegiatan <span class="text-red-400">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $kegiatan->nama) }}"
                        class="form-input-premium @error('nama') border-red-400 @enderror">
                    @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label-premium">Keterangan</label>
                    <textarea name="keterangan" rows="2"
                        class="form-input-premium resize-none">{{ old('keterangan', $kegiatan->keterangan) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="px-8 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:bg-indigo-700 transition-all flex items-center gap-2 text-sm">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Perbarui Kegiatan
                    </button>
                    <a href="{{ route('kegiatans.index') }}"
                        class="px-6 py-3 text-slate-500 font-bold hover:bg-slate-100 rounded-2xl transition-all text-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Daftar Sub Kegiatan dalam Kegiatan ini --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="git-branch" class="w-5 h-5 text-primary"></i>
                <h3 class="font-black text-slate-800">Sub Kegiatan dalam Kegiatan Ini</h3>
                <span class="px-2 py-0.5 bg-primary/10 text-primary text-xs font-bold rounded-full">
                    {{ $kegiatan->subKegiatans->count() }}
                </span>
            </div>
            <a href="{{ route('sub-kegiatans.create', ['kegiatan_id' => $kegiatan->id]) }}"
                class="px-4 py-2 bg-primary text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center gap-2">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                Tambah Sub Kegiatan
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Sub Kegiatan</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode Rekening</th>
                        <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kegiatan->subKegiatans as $sub)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-3 text-xs font-mono text-slate-500">{{ $sub->kode ?? '-' }}</td>
                        <td class="px-6 py-3 font-semibold text-slate-700">{{ $sub->nama }}</td>
                        <td class="px-6 py-3 text-xs font-mono text-slate-500">{{ $sub->kode_rek ?? '-' }}</td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('sub-kegiatans.edit', $sub->id) }}"
                                    class="p-1.5 hover:bg-blue-50 text-slate-400 hover:text-blue-600 rounded-lg transition-all">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                </a>
                                <form action="{{ route('sub-kegiatans.destroy', $sub->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus sub kegiatan ini?')">
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
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">
                            Belum ada sub kegiatan.
                            <a href="{{ route('sub-kegiatans.create', ['kegiatan_id' => $kegiatan->id]) }}"
                                class="text-primary font-bold hover:underline ml-1">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
