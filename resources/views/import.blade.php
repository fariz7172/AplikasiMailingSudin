@extends('layouts.admin')

@section('title', 'Import Data Excel')
@section('page_title', 'Manajemen Import Data')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Info Card -->
    <div class="bg-primary/5 border border-primary/20 rounded-3xl p-6 flex items-start gap-4">
        <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center shrink-0">
            <i data-lucide="info" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="font-bold text-primary">Informasi Import</h3>
            <p class="text-sm text-slate-600 leading-relaxed mt-1">
                Sistem akan membaca file Excel dan secara otomatis membagi data ke dalam tabel <strong>PPTK, Vendor, Kontrak,</strong> dan <strong>Pembayaran</strong>. Pastikan format kolom sesuai dengan template yang telah disepakati.
            </p>
        </div>
    </div>

    <!-- Main Import Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold">Proses Import</h3>
                    <p class="text-sm text-slate-400">Silakan pilih metode import data Anda.</p>
                </div>
                <div class="px-4 py-2 bg-accent/10 text-accent rounded-xl text-xs font-bold uppercase tracking-wider">
                    Excel (.xlsx) Only
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Option 1: Export Data -->
                <div class="p-6 rounded-3xl border-2 border-primary bg-primary/5 relative overflow-hidden group transition-all">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-primary/10 rounded-full group-hover:scale-150 transition-transform"></div>
                    
                    <div class="relative">
                        <div class="w-12 h-12 bg-primary text-white shadow-lg shadow-primary/30 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:rotate-12">
                            <i data-lucide="download" class="w-6 h-6"></i>
                        </div>
                        <h4 class="font-bold text-lg">Export ke Excel</h4>
                        <p class="text-xs text-slate-500 mt-1 mb-6">Unduh seluruh data transaksi dari database ke file Excel.</p>
                        
                        <div class="flex items-center gap-2 mb-6">
                            <span class="px-2 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-400">TOTAL: {{ \App\Models\Payment::count() }} Baris</span>
                        </div>

                        <a href="{{ route('export.data') }}" class="w-full py-3 bg-primary hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                            <i data-lucide="file-down" class="w-5 h-5"></i>
                            Download Data
                        </a>
                    </div>
                </div>

                <!-- Option 2: Upload New -->
                <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data" class="p-6 rounded-3xl border-2 border-dashed border-slate-200 hover:border-primary hover:bg-primary/5 transition-all group cursor-pointer flex flex-col items-center justify-center text-center" onclick="document.getElementById('file-upload').click()">
                    @csrf
                    <div class="w-12 h-12 bg-slate-100 text-slate-400 group-hover:bg-primary group-hover:text-white rounded-2xl flex items-center justify-center mb-4 transition-all group-hover:scale-110">
                        <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-bold text-lg group-hover:text-primary transition-colors">Upload Manual</h4>
                    <p class="text-xs text-slate-500 mt-1">Seret file ke sini atau klik untuk memilih file baru.</p>
                    
                    <input type="file" name="file" class="hidden" id="file-upload" onchange="this.form.submit()" accept=".xlsx, .xls">
                    <button type="button" class="mt-6 px-6 py-2 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-500 group-hover:border-primary group-hover:text-primary transition-all pointer-events-none">
                        Pilih File
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer Card -->
        <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                <i data-lucide="clock" class="w-3 h-3"></i> Terakhir import: Belum pernah
            </div>
            <a href="{{ route('import.template') }}" class="text-xs text-primary font-bold hover:underline flex items-center gap-2 transition-all hover:scale-105">
                <i data-lucide="download-cloud" class="w-4 h-4"></i>
                Download Template Excel
            </a>
        </div>
    </div>
</div>
@endsection
