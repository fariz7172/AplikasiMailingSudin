@extends('layouts.admin')

@section('title', 'Data Transaksi Keuangan')
@section('page_title', 'Daftar Transaksi SPP / SPM / SP2D')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    showSpmDoc: false,
    showKwiDoc: false,
    payment: {},
    openModal(data) {
        this.payment = data;
        this.showModal = true;
    },
    formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString;
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
    },
    generateTerbilang(angka) {
        angka = Math.floor(angka); if (angka <= 0) return '';
        let suar = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        let temp = '';
        if (angka < 12) temp = ' ' + suar[angka];
        else if (angka < 20) temp = this.generateTerbilang(angka - 10) + ' Belas';
        else if (angka < 100) temp = this.generateTerbilang(Math.floor(angka / 10)) + ' Puluh' + this.generateTerbilang(angka % 10);
        else if (angka < 200) temp = ' Seratus' + this.generateTerbilang(angka - 100);
        else if (angka < 1000) temp = this.generateTerbilang(Math.floor(angka / 100)) + ' Ratus' + this.generateTerbilang(angka % 100);
        else if (angka < 2000) temp = ' Seribu' + this.generateTerbilang(angka - 1000);
        else if (angka < 1000000) temp = this.generateTerbilang(Math.floor(angka / 1000)) + ' Ribu' + this.generateTerbilang(angka % 1000);
        else if (angka < 1000000000) temp = this.generateTerbilang(Math.floor(angka / 1000000)) + ' Juta' + this.generateTerbilang(angka % 1000000);
        else if (angka < 1000000000000) temp = this.generateTerbilang(Math.floor(angka / 1000000000)) + ' Miliar' + this.generateTerbilang(angka % 1000000000);
        return temp;
    },
    terbilangTeks(uppercase = false) {
        if (!this.payment || (!this.payment.terbilang && !this.payment.jumlah)) return '';
        let hasil = this.payment.terbilang ? this.payment.terbilang : this.generateTerbilang(this.payment.jumlah).trim();
        if (!hasil) return '';
        let finalStr = hasil + ' Rupiah';
        return uppercase ? finalStr.toUpperCase() : finalStr.toLowerCase();
    }
}">
    
    <!-- Action Bar -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('payments.index') }}" method="GET" class="relative w-full md:w-96 group">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-primary transition-colors"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Vendor, No. SPM, atau SP2D..." class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-2xl outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm text-sm">
        </form>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <a href="{{ route('import.index') }}" class="flex-1 md:flex-none px-6 py-3 bg-white text-slate-600 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all flex items-center justify-center gap-2 text-sm">
                <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                Import Excel
            </a>
            <a href="{{ route('payments.create') }}" class="flex-1 md:flex-none px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/30 hover:bg-indigo-700 transition-all flex items-center justify-center gap-2 text-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Transaksi
            </a>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No SPM</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Detail Dokumen</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Perusahaan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 text-slate-400 font-medium">{{ $loop->iteration + ($payments->firstItem() - 1) }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800">{{ $payment->no_spm ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-1 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black rounded uppercase">SPM</span>
                                    <span class="text-[11px] text-slate-600 truncate max-w-[80px]">{{ $payment->no_spm ?? '-' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="px-1 py-0.5 bg-purple-50 text-purple-600 text-[9px] font-black rounded uppercase">SPD</span>
                                    <span class="text-[11px] text-slate-600 truncate max-w-[80px]">{{ $payment->no_spd ?? '-' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="px-1 py-0.5 bg-amber-50 text-amber-600 text-[9px] font-black rounded uppercase">KWI</span>
                                    <span class="text-[11px] text-slate-600 truncate max-w-[80px]">{{ $payment->no_kwi ?? '-' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="px-1 py-0.5 bg-indigo-50 text-indigo-600 text-[9px] font-black rounded uppercase">SPP</span>
                                    <span class="text-[11px] text-slate-600 truncate max-w-[80px]">{{ $payment->no_spp ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-[200px]">
                                <span class="font-bold text-slate-700 block truncate">{{ $payment->vendor->nama_perusahaan ?? '-' }}</span>
                                <span class="text-[10px] text-slate-400 block truncate">{{ $payment->vendor->nama_direktur ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 {{ $payment->progress == 'Selesai' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }} text-[10px] font-bold rounded-full uppercase">
                                {{ $payment->progress ?? 'Proses' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openModal({{ $payment->toJson() }})" class="p-2 hover:bg-slate-100 text-slate-400 hover:text-primary rounded-xl transition-all" title="Detail Lengkap">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <a href="{{ route('payments.edit', $payment->id) }}" class="p-2 hover:bg-blue-50 text-slate-400 hover:text-blue-600 rounded-xl transition-all" title="Edit Data">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <div x-data="{ openPrint: false }" class="relative">
                                    <button @click="openPrint = !openPrint" @click.away="openPrint = false" class="p-2 hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 rounded-xl transition-all" title="Cetak Laporan">
                                        <i data-lucide="printer" class="w-4 h-4"></i>
                                    </button>
                                    <div x-show="openPrint" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-xl shadow-lg z-50 overflow-hidden text-left" style="display: none;">
                                        <a href="{{ route('payments.print', $payment->id) }}" target="_blank" class="block px-4 py-2.5 text-xs text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 font-bold border-b border-slate-50">1) Full Cetak</a>
                                        <a href="{{ route('payments.print', $payment->id) }}?type=spm" target="_blank" class="block px-4 py-2.5 text-xs text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 font-bold border-b border-slate-50">2) Print SPM</a>
                                        <a href="{{ route('payments.print', $payment->id) }}?type=kontrak" target="_blank" class="block px-4 py-2.5 text-xs text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 font-bold border-b border-slate-50">3) Nilai Kontrak</a>
                                        <a href="{{ route('payments.print', $payment->id) }}?type=sptjm_gu" target="_blank" class="block px-4 py-2.5 text-xs text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 font-bold border-b border-slate-50">4) STPJM GU</a>
                                        <a href="{{ route('payments.print', $payment->id) }}?type=sptjm_ls" target="_blank" class="block px-4 py-2.5 text-xs text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 font-bold border-b border-slate-50">5) STPJM LS</a>
                                        <a href="{{ route('payments.print', $payment->id) }}?type=gaji" target="_blank" class="block px-4 py-2.5 text-xs text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 font-bold">6) Gaji</a>
                                    </div>
                                </div>
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-xl transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <i data-lucide="folder-open" class="w-12 h-12 mb-3 opacity-20 text-primary"></i>
                                <p class="text-sm">Tidak ada data transaksi ditemukan.</p>
                                <a href="{{ route('payments.create') }}" class="mt-4 text-primary font-bold text-xs hover:underline">Tambah data pertama Anda</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $payments->links() }}
        </div>
    </div>

    
    <!-- Modal Detail -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-cloak>
        
        <div @click.away="showModal = false" class="bg-white w-full max-w-4xl max-h-[90vh] rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col border border-slate-100">
            <!-- Modal Header -->
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center">
                        <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800">Detail Transaksi</h2>
                        <p class="text-xs text-slate-400 uppercase tracking-widest font-bold">Informasi Lengkap Pembayaran</p>
                    </div>
                </div>
                <button @click="showModal = false" class="p-2 hover:bg-rose-50 text-slate-400 hover:text-rose-500 rounded-xl transition-all">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Section I: Anggaran -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-primary">
                            <i data-lucide="component" class="w-4 h-4"></i>
                            <h4 class="font-black text-sm uppercase tracking-wider">I. Data Anggaran</h4>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-5 space-y-3 border border-slate-100">
                            <div class="flex justify-between border-b border-slate-200/50 pb-2">
                                <span class="text-xs text-slate-400 font-bold">No. SPD</span>
                                <span class="text-xs font-black text-slate-700" x-text="payment.no_spd || '-'"></span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-slate-400 font-bold">Program</span>
                                <span class="text-sm font-bold text-slate-800" x-text="(payment.program_ref && payment.program_ref.nama) ? payment.program_ref.nama : (payment.program || '-')"></span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-slate-400 font-bold">Kegiatan</span>
                                <span class="text-xs text-slate-600 leading-relaxed" x-text="(payment.kegiatan_ref && payment.kegiatan_ref.nama) ? payment.kegiatan_ref.nama : (payment.kegiatan || '-')"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Section II: Kontrak -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-emerald-500">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <h4 class="font-black text-sm uppercase tracking-wider">II. Kontrak & Nilai</h4>
                        </div>
                        <div class="bg-emerald-50/30 rounded-2xl p-5 space-y-3 border border-emerald-100/50">
                            <div class="flex justify-between border-b border-emerald-200/30 pb-2">
                                <span class="text-xs text-emerald-600/70 font-bold">No. Kontrak</span>
                                <span class="text-xs font-black text-emerald-700" x-text="(payment.contract && payment.contract.nomor_kontrak) ? payment.contract.nomor_kontrak : '-'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-emerald-600/70 font-bold">Nilai Kontrak</span>
                                <span class="text-sm font-black text-emerald-600" x-text="(payment.jumlah) ? 'Rp ' + Number(payment.jumlah).toLocaleString('id-ID') : 'Rp 0'"></span>
                            </div>
                            <div class="pt-2">
                                <span class="text-[10px] text-slate-400 font-bold italic block mb-1">Terbilang:</span>
                                <p class="text-[11px] text-slate-500 italic bg-white/50 p-2 rounded-lg" x-text="terbilangTeks(true) || '-'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Section III: Dokumen -->
                    <div class="space-y-4 md:col-span-2">
                        <div class="flex items-center gap-2 text-amber-500">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                            <h4 class="font-black text-sm uppercase tracking-wider">III. Dokumen Pembayaran</h4>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div @click="showSpmDoc = true" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm cursor-pointer hover:bg-amber-50 hover:border-amber-200 hover:ring-2 hover:ring-amber-500/20 transition-all group">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase group-hover:text-amber-600 transition-colors">No. SPM</p>
                                    <i data-lucide="external-link" class="w-3 h-3 text-slate-300 group-hover:text-amber-500"></i>
                                </div>
                                <p class="text-xs font-black text-slate-700 group-hover:text-amber-700" x-text="payment.no_spm || '-'"></p>
                            </div>
                            <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm">
                                <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">No. SPP</p>
                                <p class="text-xs font-black text-slate-700" x-text="payment.no_spp || '-'"></p>
                            </div>
                            <div @click="showKwiDoc = true" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm cursor-pointer hover:bg-emerald-50 hover:border-emerald-200 hover:ring-2 hover:ring-emerald-500/20 transition-all group">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase group-hover:text-emerald-600 transition-colors">No. KWI</p>
                                    <i data-lucide="external-link" class="w-3 h-3 text-slate-300 group-hover:text-emerald-500"></i>
                                </div>
                                <p class="text-xs font-black text-slate-700 group-hover:text-emerald-700" x-text="payment.no_kwi || '-'"></p>
                            </div>
                            <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm">
                                <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">No. SP2D</p>
                                <p class="text-xs font-black text-slate-700" x-text="payment.no_sp2d || '-'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Section IV: Vendor -->
                    <div class="space-y-4 md:col-span-2">
                        <div class="flex items-center gap-2 text-indigo-500">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                            <h4 class="font-black text-sm uppercase tracking-wider">IV. Informasi Vendor</h4>
                        </div>
                        <div class="bg-indigo-50/30 rounded-2xl p-6 border border-indigo-100/50 flex flex-col md:flex-row gap-6">
                            <div class="flex-1 space-y-2">
                                <p class="text-[10px] text-indigo-400 font-bold uppercase">Perusahaan / Vendor</p>
                                <p class="text-lg font-black text-indigo-900" x-text="payment.vendor?.nama_perusahaan || '-'"></p>
                                <p class="text-xs text-slate-500 flex items-center gap-2">
                                    <i data-lucide="user" class="w-3 h-3"></i>
                                    <span x-text="payment.vendor?.direktur || '-'"></span> (Direktur)
                                </p>
                            </div>
                            <div class="md:w-px md:bg-indigo-200/50"></div>
                            <div class="space-y-3 min-w-[200px]">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                        <i data-lucide="landmark" class="w-4 h-4 text-indigo-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase leading-none mb-1">Bank & Rekening</p>
                                        <p class="text-xs font-black text-slate-700" x-text="payment.vendor?.bank || '-'"></p>
                                        <p class="text-[11px] text-slate-500 font-medium" x-text="payment.vendor?.no_rekening || '-'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button @click="showModal = false" class="px-6 py-2.5 text-slate-500 font-bold hover:bg-slate-200 rounded-xl transition-all text-sm">Tutup</button>
                <a :href="'/payments/' + payment.id + '/edit'" class="px-8 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all text-sm flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    Edit Data Ini
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Preview Dokumen SPM -->
    <div x-show="showSpmDoc" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-cloak>
        
        <div @click.away="showSpmDoc = false" class="bg-slate-100 w-full max-w-5xl max-h-[95vh] rounded-[2rem] shadow-2xl overflow-hidden flex flex-col border border-slate-200">
            <!-- Header -->
            <div class="px-6 py-4 bg-white border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-800">Preview Dokumen SPM</h2>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Verifikasi PPK & SPTJM</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a :href="'/payments/' + payment.id + '/print'" target="_blank" class="px-4 py-2 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-700 transition-all text-xs flex items-center gap-2">
                        <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak Penuh
                    </a>
                    <button @click="showSpmDoc = false" class="p-2 hover:bg-rose-50 text-slate-400 hover:text-rose-500 rounded-xl transition-all">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar flex flex-col items-center gap-8 bg-slate-200">
                
                <!-- 1. Kertas A4: SPTJM GANTI UANG -->
                <div class="bg-white w-[210mm] min-h-[297mm] p-[1.2cm] shadow-xl font-serif text-slate-900 flex flex-col">
                    <div class="flex items-center border-b-[3px] border-black pb-2 mb-8 text-center">
                        <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full" alt="Logo"></div>
                        <div class="flex-1 text-center">
                            <h1 class="text-[12pt] font-bold leading-tight uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                            <h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2>
                            <h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                        </div>
                    </div>
                    <div class="text-center mb-8">
                        <h1 class="text-[12pt] font-black uppercase underline leading-tight text-center">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK GANTI UANG</h1>
                        <p class="font-bold mt-2">Nomor : <span x-text="payment.no_spm || '-'"></span></p>
                    </div>
                    <p class="text-justify leading-relaxed text-[10pt] mb-4">
                        Sehubungan dengan Surat Perintah Membayar (SPM-GU) nomor <span class="font-bold" x-text="payment.no_spm || '-'"></span> 
                        tanggal <span x-text="formatDate(payment.tgl_spm)"></span> yang saya ajukan sebesar Rp. <span x-text="payment.contract?.nilai_kontrak ? new Intl.NumberFormat('id-ID').format(payment.contract.nilai_kontrak) : '0'"></span> 
                        (<span x-text="terbilangTeks(false) || '-'"></span>) 
                        untuk keperluan SKPD/ UNIT SKPD Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara Tahun Anggaran 2026 dengan ini menyatakan dengan sebenarnya bahwa:
                    </p>
                    <ol class="list-decimal ml-8 space-y-2 text-[10pt] text-justify leading-relaxed mb-4">
                        <li>Bukti Pertanggungjawaban atas pengunaan Ganti Uang (GU) telah lengkap, diverifikasi, dan mendapat pengesahaan.</li>
                        <li>Saya bertanggungjawab secara penuh atas penggunaan (GU) tersebut diatas sesuai dengan ketentuan peraturan perudang-undangan.</li>
                        <li>Jumlah (GU) tersebut diatas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanakan sesuai DPA/DPPA-SKPD/UNIT SKPD.</li>
                        <li>Jumlah (GU) tersebut diatas tidak akan kami gunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku harus dilakukan dengan pembayaran langsung.</li>
                        <li>Apabila dikemudian hari terdapat kelebihan pembayaran atas belanja tersebut, saya bersedia untuk menyetor kelebihannya ke kas umum daerah.</li>
                    </ol>
                    <p class="text-justify text-[10pt] leading-relaxed mb-8">Demikian Surat Pernyataan ini dibuat untuk melengkapi persyaratan SPM-GU SKPD/UNIT SKPD saya.</p>
                    <div class="flex flex-col items-end mr-4">
                        <div class="text-center min-w-[350px] text-[10pt]">
                            <p>Jakarta, <span x-text="formatDate(payment.tgl_spm)"></span></p>
                            <p class="mt-1 text-center">Kepala Suku Dinas Sumber Daya Air<br>Kota Administrasi Jakarta Utara</p>
                            <div class="mt-24">
                                <p class="font-bold underline uppercase text-center">HERIA SUWANDI</p>
                                <p class="text-center">NIP. 197101272006041009</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Kertas A4: SPTJM UANG PERSEDIAAN -->
                <div class="bg-white w-[210mm] min-h-[297mm] p-[1.2cm] shadow-xl font-serif text-slate-900 flex flex-col">
                    <div class="flex items-center border-b-[3px] border-black pb-2 mb-8 text-center">
                        <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full" alt="Logo"></div>
                        <div class="flex-1 text-center">
                            <h1 class="text-[12pt] font-bold leading-tight uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                            <h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2>
                            <h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                        </div>
                    </div>
                    <div class="text-center mb-8">
                        <h1 class="text-[12pt] font-black uppercase underline leading-tight text-center">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK UANG PERSEDIAAN</h1>
                        <p class="font-bold mt-2">Nomor : <span x-text="payment.no_spm || '-'"></span></p>
                    </div>
                    <p class="text-justify leading-relaxed text-[10pt] mb-4">
                        Sehubungan dengan Surat Perintah Membayar (SPM-UP) nomor <span class="font-bold" x-text="payment.no_spm || '-'"></span> 
                        tanggal <span x-text="formatDate(payment.tgl_spm)"></span> yang saya ajukan sebesar Rp. <span x-text="payment.contract?.nilai_kontrak ? new Intl.NumberFormat('id-ID').format(payment.contract.nilai_kontrak) : '0'"></span> 
                        (<span x-text="terbilangTeks(false) || '-'"></span>) 
                        untuk keperluan SKPD/ UNIT SKPD Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara Tahun Anggaran 2026 dengan ini menyatakan dengan sebenarnya bahwa:
                    </p>
                    <ol class="list-decimal ml-8 space-y-2 text-[10pt] text-justify leading-relaxed mb-4">
                        <li>Bukti Pertanggungjawaban atas pengunaan Ganti Uang (UP) telah lengkap, diverifikasi, dan mendapat pengesahaan.</li>
                        <li>Saya bertanggungjawab secara penuh atas penggunaan (UP) tersebut diatas sesuai dengan ketentuan peraturan perudang-undangan.</li>
                        <li>Jumlah (UP) tersebut diatas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanakan sesuai DPA/DPPA-SKPD/UNIT SKPD.</li>
                        <li>Jumlah (UP) tersebut diatas tidak akan kami gunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku harus dilakukan dengan pembayaran langsung.</li>
                        <li>Apabila dikemudian hari terdapat kelebihan pembayaran atas belanja tersebut, saya bersedia untuk menyetor kelebihannya ke kas umum daerah.</li>
                    </ol>
                    <p class="text-justify text-[10pt] leading-relaxed mb-8">Demikian Surat Pernyataan ini dibuat untuk melengkapi persyaratan SPM-UP SKPD/UNIT SKPD saya.</p>
                    <div class="flex flex-col items-end mr-4">
                        <div class="text-center min-w-[350px] text-[10pt]">
                            <p>Jakarta, <span x-text="formatDate(payment.tgl_spm)"></span></p>
                            <p class="mt-1 text-center">Kepala Suku Dinas Sumber Daya Air<br>Kota Administrasi Jakarta Utara</p>
                            <div class="mt-24">
                                <p class="font-bold underline uppercase text-center">HERIA SUWANDI</p>
                                <p class="text-center">NIP. 197101272006041009</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Kertas A4: SPTJM LS -->
                <div class="bg-white w-[210mm] min-h-[297mm] p-[1.2cm] shadow-xl font-serif text-slate-900 flex flex-col">
                    <div class="flex items-center border-b-[3px] border-black pb-2 mb-8 text-center">
                        <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full" alt="Logo"></div>
                        <div class="flex-1 text-center">
                            <h1 class="text-[12pt] font-bold leading-tight uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                            <h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2>
                            <h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                        </div>
                    </div>
                    <div class="text-center mb-8">
                        <h1 class="text-[12pt] font-black uppercase underline leading-tight text-center">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK LS</h1>
                        <p class="font-bold mt-2">Nomor : <span x-text="payment.no_spm || '-'"></span></p>
                    </div>
                    <p class="text-justify leading-relaxed text-[10pt] mb-4">
                        Sehubungan dengan Surat Perintah Membayar (SPM-LS) nomor <span class="font-bold" x-text="payment.no_spm || '-'"></span> 
                        tanggal <span x-text="formatDate(payment.tgl_spm)"></span> yang saya ajukan sebesar Rp. <span x-text="payment.contract?.nilai_kontrak ? new Intl.NumberFormat('id-ID').format(payment.contract.nilai_kontrak) : '0'"></span> 
                        (<span x-text="terbilangTeks(false) || '-'"></span>) 
                        untuk keperluan SKPD/ UNIT SKPD Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara Tahun Anggaran 2026 dengan ini menyatakan dengan sebenarnya bahwa:
                    </p>
                    <ol class="list-decimal ml-8 space-y-2 text-[10pt] text-justify leading-relaxed mb-4">
                        <li>Saya bertanggung jawab secara penuh atas penggunaan (LS) tersebut diatas yang mengakibatkan pengeluaran atas beban anggaran belanja dan/atau pengeluaran pembiayaan sesuai dengan ketentuan peraturan perundang-undangan.</li>
                        <li>Jumlah (LS) tersebut diatas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan saya laksanakan sesuai DPA/ DPPA-SKPD/ UNIT SKPD.</li>
                        <li>Jumlah (LS) tersebut diatas tidak akan saya gunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku harus dilakukan dengan pembayaran lainnya.</li>
                        <li>Apabila dikemudian hari terdapat kelebihan pembayaran atas belanja tersebut, saya bersedia untuk menyetor kelebihannya ke kas umum daerah.</li>
                    </ol>
                    <p class="text-justify text-[10pt] leading-relaxed mb-8">Demikian Surat Pernyataan ini dibuat untuk melengkapi persyaratan SPM-LS SKPD/UNIT SKPD saya.</p>
                    <br><br><br>
                    <div class="grid grid-cols-2 text-center gap-4 px-4 text-[10pt] leading-tight mt-8">
                        <div class="flex flex-col items-center">
                            <p>Jakarta, <span x-text="formatDate(payment.tgl_spm)"></span></p>
                            <p class="font-bold uppercase mt-2 text-center">Pejabat Pelaksana Teknis Kegiatan <br><br></p>
                            <div class="mt-24">
                                <p class="font-bold underline uppercase text-center" x-text="payment.pptk?.nama || '-'"></p>
                                <p class="text-center">NIP. <span x-text="payment.pptk?.nip || '-'"></span></p>
                            </div>
                        </div>
                        <div class="flex flex-col items-center">
                            <p>Jakarta, <span x-text="formatDate(payment.tgl_spm)"></span></p>
                            <p class="font-bold uppercase mt-2 text-center">Kepala Suku Dinas Sumber Daya Air<br>Kota Administrasi Jakarta Utara</p>
                            <div class="mt-24">
                                <p class="font-bold underline uppercase text-center">HERIA SUWANDI</p>
                                <p class="text-center">NIP. 197101272006041009</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal Preview Dokumen KWI -->
    <div x-show="showKwiDoc" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-cloak>
        
        <div @click.away="showKwiDoc = false" class="bg-slate-100 w-full max-w-5xl max-h-[95vh] rounded-[2rem] shadow-2xl overflow-hidden flex flex-col border border-slate-200">
            <!-- Header -->
            <div class="px-6 py-4 bg-white border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="receipt" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-800">Preview Dokumen KWI</h2>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Kwitansi Pembayaran</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a :href="'/payments/' + payment.id + '/print'" target="_blank" class="px-4 py-2 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-700 transition-all text-xs flex items-center gap-2">
                        <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak Penuh
                    </a>
                    <button @click="showKwiDoc = false" class="p-2 hover:bg-rose-50 text-slate-400 hover:text-rose-500 rounded-xl transition-all">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar flex flex-col items-center gap-8 bg-slate-200">
                
                <!-- 1. Kertas A4: KWITANSI -->
                <div class="bg-white w-[210mm] min-h-[297mm] shadow-xl font-serif text-slate-900 flex flex-col">
                    <div class="border-[1.5px] border-black p-8 m-8 flex-1">
                        <div class="flex items-center border-b-[2px] border-black pb-2 mb-6 text-center">
                            <div class="w-[80px] pr-3"><img src="{{ asset('assets/logo.png') }}" class="w-full" alt="Logo"></div>
                            <div class="flex-1">
                                <h1 class="text-[10pt] font-bold uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                                <h2 class="text-[12pt] font-bold uppercase text-center text-center">DINAS SUMBER DAYA AIR</h2>
                                <h3 class="text-[10pt] font-bold uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                            </div>
                        </div>
                        <div class="text-center mb-6"><h1 class="text-[16pt] font-black underline tracking-widest uppercase">KWITANSI</h1></div>
                        <div class="space-y-4 px-4 text-[11pt]">
                            <div class="grid gap-x-2" style="grid-template-columns: 160px 10px 1fr;"><span>Nomor</span><span>:</span><span class="font-bold" x-text="payment.no_kwi || '-'"></span></div>
                            <div class="grid gap-x-2 min-h-[50px]" style="grid-template-columns: 160px 10px 1fr;"><span>Jumlah</span><span>:</span><span class="font-bold italic uppercase" x-text="'# ' + terbilangTeks(true) + ' #'"></span></div>
                            <div class="grid gap-x-2" style="grid-template-columns: 160px 10px 1fr;"><span>Pembayaran</span><span>:</span><span class="leading-relaxed" x-text="payment.keperluan || '-'"></span></div>
                        </div>
                        <div class="mt-10 flex justify-between border-t-2 border-b-2 border-black py-4 px-6 bg-slate-50 font-black text-[14pt]">
                            <span>JUMLAH Rp.</span><span x-text="payment.contract?.nilai_kontrak ? new Intl.NumberFormat('id-ID').format(payment.contract.nilai_kontrak) : '0'"></span>
                        </div>
                        <div class="mt-8 flex justify-between px-4 text-[9pt]">
                            <div class="flex-1"></div>
                            <div class="text-left min-w-[250px]"><p>Jakarta, <span x-text="formatDate(payment.tgl_kwi)"></span></p></div>
                        </div>
                        <div class="grid grid-cols-2 text-center gap-4 px-4 text-[9pt] leading-tight mt-8">
                            <div>
                                <p class="font-bold uppercase">Pejabat Pelaksana Teknis Kegiatan</p>
                                <p class="font-bold uppercase">Suku Dinas Sumber Daya Air</p>
                                <p class="font-bold uppercase text-center">Kota Administrasi Jakarta Utara</p>
                                <div class="mt-20">
                                    <p class="font-bold underline uppercase" x-text="payment.pptk?.nama || '-'"></p>
                                    <p>NIP. <span x-text="payment.pptk?.nip || '-'"></span></p>
                                </div>
                            </div>
                            <div>
                                <p class="font-bold uppercase text-center">Bendahara Pengeluaran Pembantu</p>
                                <p class="font-bold uppercase">Suku Dinas Sumber Daya Air</p>
                                <p class="font-bold uppercase text-center">Kota Administrasi Jakarta Utara</p>
                                <div class="mt-20">
                                    <p class="font-bold underline uppercase">R. Elly Prasojo</p>
                                    <p>NIP. 197410252014121001</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex flex-col items-center text-center text-[9pt] leading-tight">
                            <p class="font-bold uppercase">Mengetahui</p>
                            <p class="font-bold uppercase text-center">KEPALA SUKU DINAS SUMBER DAYA AIR</p>
                            <p class="font-bold uppercase text-center">KOTA ADMINISTRASI JAKARTA UTARA</p>
                            <div class="mt-20">
                                <p class="font-bold underline uppercase">HERIA SUWANDI</p>
                                <p>NIP. 197101272006041009</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Kertas A4: KWITANSI (TANPA PPTK) -->
                <div class="bg-white w-[210mm] min-h-[297mm] shadow-xl font-serif text-slate-900 flex flex-col">
                    <div class="border-[1.5px] border-black p-8 m-8 flex-1">
                        <div class="flex items-center border-b-[2px] border-black pb-2 mb-6 text-center">
                            <div class="w-[80px] pr-3"><img src="{{ asset('assets/logo.png') }}" class="w-full" alt="Logo"></div>
                            <div class="flex-1">
                                <h1 class="text-[10pt] font-bold uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                                <h2 class="text-[12pt] font-bold uppercase text-center text-center">DINAS SUMBER DAYA AIR</h2>
                                <h3 class="text-[10pt] font-bold uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                            </div>
                        </div>
                        <div class="text-center mb-6"><h1 class="text-[16pt] font-black underline tracking-widest uppercase">KWITANSI</h1></div>
                        <div class="space-y-4 px-4 text-[11pt]">
                            <div class="grid gap-x-2" style="grid-template-columns: 160px 10px 1fr;"><span>Nomor</span><span>:</span><span class="font-bold" x-text="payment.no_kwi || '-'"></span></div>
                            <div class="grid gap-x-2 min-h-[50px]" style="grid-template-columns: 160px 10px 1fr;"><span>Jumlah</span><span>:</span><span class="font-bold italic uppercase" x-text="'# ' + terbilangTeks(true) + ' #'"></span></div>
                            <div class="grid gap-x-2" style="grid-template-columns: 160px 10px 1fr;"><span>Pembayaran</span><span>:</span><span class="leading-relaxed" x-text="payment.keperluan || '-'"></span></div>
                        </div>
                        <div class="mt-10 flex justify-between border-t-2 border-b-2 border-black py-4 px-6 bg-slate-50 font-black text-[14pt]">
                            <span>JUMLAH Rp.</span><span x-text="payment.contract?.nilai_kontrak ? new Intl.NumberFormat('id-ID').format(payment.contract.nilai_kontrak) : '0'"></span>
                        </div>
                        <div class="mt-8 flex justify-between px-4 text-[9pt]">
                            <div class="flex-1"></div>
                            <div class="text-left min-w-[250px]"><p>Jakarta, <span x-text="formatDate(payment.tgl_kwi)"></span></p></div>
                        </div>
                        <div class="grid grid-cols-2 text-center gap-4 px-4 text-[9pt] leading-tight mt-8">
                            <div></div>
                            <div>
                                <p class="font-bold uppercase text-center">Bendahara Pengeluaran Pembantu</p>
                                <p class="font-bold uppercase">Suku Dinas Sumber Daya Air</p>
                                <p class="font-bold uppercase text-center">Kota Administrasi Jakarta Utara</p>
                                <div class="mt-20">
                                    <p class="font-bold underline uppercase">R. Elly Prasojo</p>
                                    <p>NIP. 197410252014121001</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 flex flex-col items-center text-center text-[9pt] leading-tight">
                            <p class="font-bold uppercase">Mengetahui</p>
                            <p class="font-bold uppercase text-center">KEPALA SUKU DINAS SUMBER DAYA AIR</p>
                            <p class="font-bold uppercase text-center">KOTA ADMINISTRASI JAKARTA UTARA</p>
                            <div class="mt-20">
                                <p class="font-bold underline uppercase">HERIA SUWANDI</p>
                                <p>NIP. 197101272006041009</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
