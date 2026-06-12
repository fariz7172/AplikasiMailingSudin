const fs = require('fs');
const path = require('path');

const viewsDir = path.join(__dirname, '..', 'resources', 'views');
const perusahaanDir = path.join(viewsDir, 'perusahaans');
if (!fs.existsSync(perusahaanDir)) fs.mkdirSync(perusahaanDir, { recursive: true });

// Read a similar index.blade.php from pptks or something, actually I will just generate a basic index, create, edit for Perusahaans.
// Actually, it's faster to just use a generic layout.

const indexBlade = `@extends('layouts.admin')

@section('title', 'Data Perusahaan')
@section('page_title', 'Data Perusahaan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-slate-800">Daftar Perusahaan</h2>
    <a href="{{ route('perusahaans.create') }}" class="px-4 py-2 bg-primary text-white rounded-xl font-bold hover:bg-primary/90 transition-colors">
        + Tambah Perusahaan
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Perusahaan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Direktur</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">NPWP</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($perusahaans as $p)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-800">{{ $p->nama_perusahaan }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $p->direktur }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $p->npwp }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('perusahaans.edit', $p->id) }}" class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold hover:bg-amber-200">Edit</a>
                        <form action="{{ route('perusahaans.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold hover:bg-rose-200">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
`;
fs.writeFileSync(path.join(perusahaanDir, 'index.blade.php'), indexBlade);

const formFields = `
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
        <input type="date" name="tgl_akte" value="{{ old('tgl_akte', $perusahaan->tgl_akte ? \\Carbon\\Carbon::parse($perusahaan->tgl_akte)->format('Y-m-d') : '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">Nomor TDP / NIB</label>
        <input type="text" name="tdp" value="{{ old('tdp', $perusahaan->tdp ?? '') }}" class="form-input-premium">
    </div>
    <div>
        <label class="form-label-premium">Tanggal TDP / NIB</label>
        <input type="date" name="tgl_tdp" value="{{ old('tgl_tdp', $perusahaan->tgl_tdp ? \\Carbon\\Carbon::parse($perusahaan->tgl_tdp)->format('Y-m-d') : '') }}" class="form-input-premium">
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
`;

const createBlade = `@extends('layouts.admin')
@section('title', 'Tambah Perusahaan')
@section('page_title', 'Tambah Perusahaan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
    <form action="{{ route('perusahaans.store') }}" method="POST">
        @csrf
        ${formFields}
        <div class="mt-8 flex justify-end">
            <a href="{{ route('perusahaans.index') }}" class="px-6 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold mr-4">Batal</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-xl font-bold">Simpan</button>
        </div>
    </form>
</div>
@endsection
`;
fs.writeFileSync(path.join(perusahaanDir, 'create.blade.php'), createBlade);

const editBlade = `@extends('layouts.admin')
@section('title', 'Edit Perusahaan')
@section('page_title', 'Edit Perusahaan')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
    <form action="{{ route('perusahaans.update', $perusahaan->id) }}" method="POST">
        @csrf
        @method('PUT')
        ${formFields}
        <div class="mt-8 flex justify-end">
            <a href="{{ route('perusahaans.index') }}" class="px-6 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold mr-4">Batal</a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-xl font-bold">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
`;
fs.writeFileSync(path.join(perusahaanDir, 'edit.blade.php'), editBlade);

// 2. Modify Payments Views to use perusahaan instead of vendor
const paymentViews = ['index.blade.php', 'create.blade.php', 'edit.blade.php', 'print.blade.php'];
paymentViews.forEach(file => {
    let p = path.join(viewsDir, 'payments', file);
    if (!fs.existsSync(p)) return;
    let content = fs.readFileSync(p, 'utf-8');

    if (file === 'create.blade.php' || file === 'edit.blade.php') {
        content = content.replace(/vendors: @json\(\$vendors\)/g, 'perusahaans: @json($perusahaans)');
        content = content.replace(/v in vendors/g, 'v in perusahaans');
        content = content.replace(/this\.vendors\.find/g, 'this.perusahaans.find');
        content = content.replace(/Buat \/ Input Vendor Baru/g, 'Buat / Input Perusahaan Baru');
        content = content.replace(/Pilih Data Vendor Tersimpan/g, 'Pilih Data Perusahaan Tersimpan');
        content = content.replace(/Data Vendor & Legalitas/g, 'Data Perusahaan & Legalitas');
        // Let's replace 'vendorData' with 'perusahaanData'
        content = content.replace(/vendorData\./g, 'perusahaanData.');
        content = content.replace(/vendorData :/g, 'perusahaanData :');
        content = content.replace(/vendorData:/g, 'perusahaanData:');
        content = content.replace(/this\.vendorData/g, 'this.perusahaanData');
        
        content = content.replace(/isNewVendor/g, 'isNewPerusahaan');
        content = content.replace(/selectedVendorId/g, 'selectedPerusahaanId');
        content = content.replace(/onVendorSelect/g, 'onPerusahaanSelect');
        content = content.replace(/currentVendorId/g, 'currentPerusahaanId');
        
        // Also update vendor_id to perusahaan_id
        content = content.replace(/name="vendor_id"/g, 'name="perusahaan_id"');
        content = content.replace(/\$payment->vendor_id/g, '$payment->perusahaan_id');
        content = content.replace(/\$payment->vendor->/g, '$payment->perusahaan->');
    }
    
    if (file === 'index.blade.php' || file === 'print.blade.php') {
        content = content.replace(/\$payment->vendor->/g, '$payment->perusahaan->');
        content = content.replace(/\$payment->vendor\?-/g, '$payment->perusahaan?-');
        content = content.replace(/payment\.vendor\?/g, 'payment.perusahaan?');
        // keep text "Vendor" visually where appropriate, or replace it if needed. The user wanted to name it Perusahaan.
        content = content.replace(/Vendor & Kontrak/g, 'Perusahaan & Kontrak');
        content = content.replace(/Informasi Vendor/g, 'Informasi Perusahaan');
    }

    fs.writeFileSync(p, content, 'utf-8');
    console.log('Updated payments view: ' + file);
});

console.log('CRUD Views for Perusahaans generated.');
