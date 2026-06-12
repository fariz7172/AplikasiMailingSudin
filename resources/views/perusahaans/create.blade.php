@extends('layouts.admin')
@section('title', 'Tambah Perusahaan')
@section('page_title', 'Tambah Perusahaan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
    <form action="{{ route('perusahaans.store') }}" method="POST">
        @csrf
        
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <label class="form-label-premium">Nama Perusahaan</label>
        <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan ?? '') }}" class="form-input-premium" required>
    </div>
    <div>
        <label class="form-label-premium">Nama Direktur</label>
        <input type="text" name="direktur" value="{{ old('direktur', $perusahaan->direktur ?? '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">NPWP</label>
        <input type="text" name="npwp" value="{{ old('npwp', $perusahaan->npwp ?? '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">Nomor Akte</label>
        <input type="text" name="akte" value="{{ old('akte', $perusahaan->akte ?? '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">Tanggal Akte</label>
        <input type="date" name="tgl_akte" value="{{ old('tgl_akte', $perusahaan->tgl_akte ? \Carbon\Carbon::parse($perusahaan->tgl_akte)->format('Y-m-d') : '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">Nomor TDP / NIB</label>
        <input type="text" name="tdp" value="{{ old('tdp', $perusahaan->tdp ?? '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">Tanggal TDP / NIB</label>
        <input type="date" name="tgl_tdp" value="{{ old('tgl_tdp', $perusahaan->tgl_tdp ? \Carbon\Carbon::parse($perusahaan->tgl_tdp)->format('Y-m-d') : '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">Nama Bank</label>
        <input type="text" name="bank" value="{{ old('bank', $perusahaan->bank ?? '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">Nomor Rekening</label>
        <input type="text" name="no_rekening" value="{{ old('no_rekening', $perusahaan->no_rekening ?? '') }}" class="form-input-premium">
    </div>
    <div class="md:col-span-2">
        <label class="form-label-premium">Alamat Perusahaan</label>
        <input type="text" name="alamat" value="{{ old('alamat', $perusahaan->alamat ?? '') }}" class="form-input-premium">
    </div>
</div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('perusahaans.index') }}" class="px-6 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold mr-4">Batal</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-xl font-bold">Simpan</button>
        </div>
    </form>
</div>
@endsection
