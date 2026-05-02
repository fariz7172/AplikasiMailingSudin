@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Ringkasan Sistem E-SPP')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Stats Cards -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
            <i data-lucide="credit-card" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Transaksi</p>
            <h3 class="text-2xl font-black text-slate-800">{{ \App\Models\Payment::count() }}</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
            <i data-lucide="users" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Vendor</p>
            <h3 class="text-2xl font-black text-slate-800">{{ \App\Models\Vendor::count() }}</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
            <i data-lucide="file-check" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kontrak Aktif</p>
            <h3 class="text-2xl font-black text-slate-800">{{ \App\Models\Contract::count() }}</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center">
            <i data-lucide="user-check" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">PPTK</p>
            <h3 class="text-2xl font-black text-slate-800">{{ \App\Models\Pptk::count() }}</h3>
        </div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Quick Actions -->
    <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-xl font-black text-slate-800">Akses Cepat</h3>
                <p class="text-sm text-slate-400">Pilih menu untuk mulai bekerja</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('payments.create') }}" class="group p-6 bg-slate-50 hover:bg-primary rounded-[2rem] transition-all duration-300">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
                    <i data-lucide="plus-circle" class="w-6 h-6 text-primary"></i>
                </div>
                <h4 class="font-bold text-slate-800 group-hover:text-white transition-colors">Tambah Data Baru</h4>
                <p class="text-xs text-slate-400 group-hover:text-white/70 transition-colors mt-1">Input data SPP/SPM baru ke sistem</p>
            </a>

            <a href="{{ route('payments.index') }}" class="group p-6 bg-slate-50 hover:bg-accent rounded-[2rem] transition-all duration-300">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
                    <i data-lucide="list" class="w-6 h-6 text-accent"></i>
                </div>
                <h4 class="font-bold text-slate-800 group-hover:text-white transition-colors">Daftar Transaksi</h4>
                <p class="text-xs text-slate-400 group-hover:text-white/70 transition-colors mt-1">Lihat dan cetak dokumen keuangan</p>
            </a>

            <a href="{{ route('import.index') }}" class="group p-6 bg-slate-50 hover:bg-emerald-500 rounded-[2rem] transition-all duration-300">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
                    <i data-lucide="upload-cloud" class="w-6 h-6 text-emerald-500"></i>
                </div>
                <h4 class="font-bold text-slate-800 group-hover:text-white transition-colors">Import Database</h4>
                <p class="text-xs text-slate-400 group-hover:text-white/70 transition-colors mt-1">Sinkronisasi data dari file Excel</p>
            </a>

            <a href="{{ route('pptk.index') }}" class="group p-6 bg-slate-50 hover:bg-amber-500 rounded-[2rem] transition-all duration-300">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
                    <i data-lucide="user-check" class="w-6 h-6 text-amber-500"></i>
                </div>
                <h4 class="font-bold text-slate-800 group-hover:text-white transition-colors">Data Pejabat</h4>
                <p class="text-xs text-slate-400 group-hover:text-white/70 transition-colors mt-1">Kelola data NIP dan Nama Pejabat</p>
            </a>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-primary rounded-[2.5rem] p-8 text-white relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="text-xl font-black mb-2">Sistem E-SPP v1.0</h3>
            <p class="text-sm text-white/70 leading-relaxed mb-8">
                Selamat bekerja, <strong>{{ auth()->user()->name }}</strong>. Anda login sebagai <strong>{{ strtoupper(auth()->user()->role) }}</strong>. Gunakan sistem ini dengan bijak untuk memproses dokumen administratif Sudin SDA Jakarta Utara.
            </p>
            
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-accent rounded-full"></div>
                    <span class="text-xs font-bold opacity-80">Backup Data Harian Otomatis</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-1.5 bg-accent rounded-full"></div>
                    <span class="text-xs font-bold opacity-80">Enkripsi Data Transaksi</span>
                </div>
            </div>
        </div>
        
        <!-- Decoration -->
        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
    </div>
</div>
@endsection
