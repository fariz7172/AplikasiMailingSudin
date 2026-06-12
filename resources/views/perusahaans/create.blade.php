@extends('layouts.admin')
@section('title', 'Tambah Perusahaan')
@section('page_title', 'Tambah Perusahaan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 max-w-2xl mx-auto">
    <form action="{{ route('perusahaans.store') }}" method="POST">
        @csrf
        
<div class="grid grid-cols-1 gap-6">
    <div>
        <label class="form-label-premium">Nama Perusahaan</label>
        <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan ?? '') }}" class="form-input-premium" required>
    </div>
    <div>
        <label class="form-label-premium">No. Telepon</label>
        <input type="text" name="no_tlp" value="{{ old('no_tlp', $perusahaan->no_tlp ?? '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">Alamat</label>
        <textarea name="alamat" rows="3" class="form-input-premium">{{ old('alamat', $perusahaan->alamat ?? '') }}</textarea>
    </div>
</div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('perusahaans.index') }}" class="px-6 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold mr-4">Batal</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-xl font-bold">Simpan</button>
        </div>
    </form>
</div>
@endsection
