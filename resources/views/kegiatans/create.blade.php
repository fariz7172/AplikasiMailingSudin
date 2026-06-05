@extends('layouts.admin')

@section('title', 'Tambah Kegiatan')
@section('page_title', 'Tambah Master Kegiatan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Header --}}
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-accent/10 text-accent rounded-2xl flex items-center justify-center">
                <i data-lucide="list-plus" class="w-6 h-6"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-800">Tambah Kegiatan Baru</h2>
                <p class="text-xs text-slate-400">Kegiatan adalah sub-kategori di bawah Program</p>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('kegiatans.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            {{-- Pilih Program --}}
            <div>
                <label class="form-label-premium">Program Induk <span class="text-red-400">*</span></label>
                <select name="program_id" id="program_id"
                    class="form-input-premium @error('program_id') border-red-400 @enderror">
                    <option value="">— Pilih Program —</option>
                    @foreach($programs as $prog)
                    <option value="{{ $prog->id }}"
                        {{ old('program_id', $selectedProgramId) == $prog->id ? 'selected' : '' }}>
                        {{ $prog->kode }} - {{ $prog->nama }} {{ $prog->tahun_anggaran ? '('.$prog->tahun_anggaran.')' : '' }}
                    </option>
                    @endforeach
                </select>
                @error('program_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                @if($programs->isEmpty())
                <p class="mt-2 text-xs text-amber-600 font-semibold">
                    ⚠ Belum ada Program. <a href="{{ route('programs.create') }}" class="text-primary hover:underline">Buat Program terlebih dahulu</a>.
                </p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label-premium">Kode Kegiatan <span class="text-slate-300 font-normal normal-case">(opsional)</span></label>
                    <input type="text" name="kode" value="{{ old('kode') }}"
                        placeholder="Contoh: 1.03.02.01"
                        class="form-input-premium @error('kode') border-red-400 @enderror">
                    @error('kode')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label-premium">Kode Rekening <span class="text-slate-300 font-normal normal-case">(opsional)</span></label>
                    <input type="text" name="kode_rek" value="{{ old('kode_rek') }}"
                        placeholder="Contoh: 5.2.2.01"
                        class="form-input-premium @error('kode_rek') border-red-400 @enderror">
                    @error('kode_rek')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label-premium">Nama Kegiatan <span class="text-red-400">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    placeholder="Contoh: Kegiatan Normalisasi Saluran"
                    class="form-input-premium @error('nama') border-red-400 @enderror">
                @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label-premium">Sub Kegiatan <span class="text-slate-300 font-normal normal-case">(opsional)</span></label>
                <input type="text" name="sub_kegiatan" value="{{ old('sub_kegiatan') }}"
                    placeholder="Contoh: Sub Kegiatan Normalisasi Sungai Cilincing"
                    class="form-input-premium @error('sub_kegiatan') border-red-400 @enderror">
                @error('sub_kegiatan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label-premium">Keterangan <span class="text-slate-300 font-normal normal-case">(opsional)</span></label>
                <textarea name="keterangan" rows="2"
                    class="form-input-premium resize-none @error('keterangan') border-red-400 @enderror">{{ old('keterangan') }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-8 py-3 bg-accent text-white font-bold rounded-2xl shadow-lg shadow-accent/20 hover:bg-sky-600 transition-all flex items-center gap-2 text-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Kegiatan
                </button>
                <a href="{{ route('kegiatans.index') }}"
                    class="px-6 py-3 text-slate-500 font-bold hover:bg-slate-100 rounded-2xl transition-all text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
