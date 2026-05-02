@extends('layouts.admin')

@section('title', 'Tambah Pejabat')
@section('page_title', 'Input Data Pejabat Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('pptk.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20">
                    <i data-lucide="user-plus" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Form Pejabat Baru</h3>
                    <p class="text-slate-500 text-sm">Lengkapi informasi pejabat pelaksana teknis</p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="form-label-premium">Nama Lengkap Pejabat</label>
                        <input type="text" name="nama" class="form-input-premium @error('nama') border-rose-500 @enderror" placeholder="Contoh: Budi Santoso, S.T." value="{{ old('nama') }}" required>
                        @error('nama') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label-premium">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" class="form-input-premium @error('nip') border-rose-500 @enderror" placeholder="Input NIP" value="{{ old('nip') }}">
                        @error('nip') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label-premium">NIK (KTP)</label>
                        <input type="text" name="nik" class="form-input-premium @error('nik') border-rose-500 @enderror" placeholder="Input NIK" value="{{ old('nik') }}">
                        @error('nik') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label-premium">Jabatan</label>
                        <input type="text" name="jabatan" class="form-input-premium @error('jabatan') border-rose-500 @enderror" placeholder="Contoh: PPTK Bidang Sumber Daya Air" value="{{ old('jabatan') }}" required>
                        @error('jabatan') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label-premium">Nomor Rekening</label>
                        <input type="text" name="no_rekening" class="form-input-premium @error('no_rekening') border-rose-500 @enderror" placeholder="Contoh: Bank DKI - 1234567890" value="{{ old('no_rekening') }}">
                        @error('no_rekening') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex items-center justify-end gap-4">
                <a href="{{ route('pptk.index') }}" class="px-6 py-3 text-slate-500 font-bold hover:text-slate-700 transition-colors">Batal</a>
                <button type="submit" class="px-10 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Simpan Data
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
