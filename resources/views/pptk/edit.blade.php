@extends('layouts.admin')

@section('title', 'Edit Pejabat')
@section('page_title', 'Perbarui Data Pejabat')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('pptk.update', $pptk) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <i data-lucide="user-cog" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Edit Data: {{ $pptk->nama }}</h3>
                    <p class="text-slate-500 text-sm">Perbarui informasi jabatan atau detail lainnya</p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="form-label-premium">Nama Lengkap Pejabat</label>
                        <input type="text" name="nama" class="form-input-premium @error('nama') border-rose-500 @enderror" placeholder="Contoh: Budi Santoso, S.T." value="{{ old('nama', $pptk->nama) }}" required>
                        @error('nama') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label-premium">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" class="form-input-premium @error('nip') border-rose-500 @enderror" placeholder="Input NIP" value="{{ old('nip', $pptk->nip) }}">
                        @error('nip') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label-premium">NIK (KTP)</label>
                        <input type="text" name="nik" class="form-input-premium @error('nik') border-rose-500 @enderror" placeholder="Input NIK" value="{{ old('nik', $pptk->nik) }}">
                        @error('nik') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label-premium">Jabatan</label>
                        <input type="text" name="jabatan" class="form-input-premium @error('jabatan') border-rose-500 @enderror" placeholder="Contoh: PPTK Bidang Sumber Daya Air" value="{{ old('jabatan', $pptk->jabatan) }}" required>
                        @error('jabatan') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label-premium">Nomor Rekening</label>
                        <input type="text" name="no_rekening" class="form-input-premium @error('no_rekening') border-rose-500 @enderror" placeholder="Contoh: Bank DKI - 1234567890" value="{{ old('no_rekening', $pptk->no_rekening) }}">
                        @error('no_rekening') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex items-center justify-end gap-4">
                <a href="{{ route('pptk.index') }}" class="px-6 py-3 text-slate-500 font-bold hover:text-slate-700 transition-colors">Batal</a>
                <button type="submit" class="px-10 py-3 bg-amber-500 text-white font-bold rounded-2xl shadow-lg shadow-amber-500/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    Perbarui Data
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
