@extends('layouts.admin')

@section('title', 'Tambah Program')
@section('page_title', 'Tambah Master Program')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Header --}}
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center">
                <i data-lucide="folder-plus" class="w-6 h-6"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-800">Tambah Program Baru</h2>
                <p class="text-xs text-slate-400">Isi data program anggaran sebagai kategori induk kegiatan</p>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('programs.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label-premium">Kode Program <span class="text-slate-300 font-normal normal-case">(opsional)</span></label>
                    <input type="text" name="kode" value="{{ old('kode') }}"
                        placeholder="Contoh: 1.03.02"
                        class="form-input-premium @error('kode') border-red-400 @enderror">
                    @error('kode')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label-premium">Tahun Anggaran <span class="text-slate-300 font-normal normal-case">(opsional)</span></label>
                    <input type="number" name="tahun_anggaran" value="{{ old('tahun_anggaran', date('Y')) }}"
                        placeholder="Contoh: 2026" min="2000" max="2099"
                        class="form-input-premium @error('tahun_anggaran') border-red-400 @enderror">
                    @error('tahun_anggaran')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label-premium">Nama Program <span class="text-red-400">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    placeholder="Contoh: Program Pengelolaan Sumber Daya Air"
                    class="form-input-premium @error('nama') border-red-400 @enderror">
                @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label-premium">Keterangan <span class="text-slate-300 font-normal normal-case">(opsional)</span></label>
                <textarea name="keterangan" rows="3"
                    placeholder="Deskripsi singkat program..."
                    class="form-input-premium resize-none @error('keterangan') border-red-400 @enderror">{{ old('keterangan') }}</textarea>
                @error('keterangan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-8 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:bg-indigo-700 transition-all flex items-center gap-2 text-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Program
                </button>
                <a href="{{ route('programs.index') }}"
                    class="px-6 py-3 text-slate-500 font-bold hover:bg-slate-100 rounded-2xl transition-all text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
