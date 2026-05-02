@extends('layouts.admin')

@section('title', 'Data Transaksi Keuangan')
@section('page_title', 'Daftar Transaksi SPP / SPM / SP2D')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    payment: {},
    openModal(data) {
        this.payment = data;
        this.showModal = true;
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
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Vendor & Kontrak</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Detail Dokumen</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Program</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 text-slate-400 font-medium">{{ $loop->iteration + ($payments->firstItem() - 1) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800">{{ $payment->vendor->nama_perusahaan ?? '-' }}</span>
                                <span class="text-[10px] text-slate-400 uppercase mt-0.5 tracking-wider">KTR: {{ $payment->contract->nomor_kontrak ?? '-' }}</span>
                            </div>
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
                                <span class="font-bold text-slate-700 block truncate">{{ $payment->program ?? '-' }}</span>
                                <span class="text-[10px] text-slate-400 block truncate">{{ $payment->kegiatan ?? '-' }}</span>
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
                                <a href="{{ route('payments.print', $payment->id) }}" class="p-2 hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 rounded-xl transition-all" title="Cetak Laporan" target="_blank">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </a>
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
                                <span class="text-sm font-bold text-slate-800" x-text="payment.program || '-'"></span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-slate-400 font-bold">Kegiatan</span>
                                <span class="text-xs text-slate-600 leading-relaxed" x-text="payment.kegiatan || '-'"></span>
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
                                <span class="text-xs font-black text-emerald-700" x-text="payment.contract?.nomor_kontrak || '-'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-emerald-600/70 font-bold">Nilai Kontrak</span>
                                <span class="text-sm font-black text-emerald-600" x-text="'Rp ' + (payment.contract?.nilai_kontrak || 0).toLocaleString('id-ID')"></span>
                            </div>
                            <div class="pt-2">
                                <span class="text-[10px] text-slate-400 font-bold italic block mb-1">Terbilang:</span>
                                <p class="text-[11px] text-slate-500 italic bg-white/50 p-2 rounded-lg" x-text="payment.contract?.terbilang_kontrak || '-'"></p>
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
                            <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm">
                                <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">No. SPM</p>
                                <p class="text-xs font-black text-slate-700" x-text="payment.no_spm || '-'"></p>
                            </div>
                            <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm">
                                <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">No. SPP</p>
                                <p class="text-xs font-black text-slate-700" x-text="payment.no_spp || '-'"></p>
                            </div>
                            <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm">
                                <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">No. KWI</p>
                                <p class="text-xs font-black text-slate-700" x-text="payment.no_kwi || '-'"></p>
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
</div>
@endsection
