@extends('layouts.admin')

@section('title', 'Cetak Slip Gaji (Checklist SPP)')
@section('page_title', 'Pilih Pejabat PPTK untuk Dicetak')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Cetak Checklist SPP (Slip Gaji)</h2>
        <p class="text-sm text-slate-500 mt-1">Pilih satu atau beberapa Pejabat Pelaksana Teknis Kegiatan (PPTK) di bawah ini untuk mencetak kelengkapan form checklist SPP (LS, UP, dan GU).</p>
    </div>

    <form action="{{ route('slip-gaji.print') }}" method="POST" target="_blank" class="space-y-6">
        @csrf
        
        <!-- Input Tanggal Cetak -->
        <div class="max-w-xs">
            <label class="form-label-premium">Tanggal Cetak (Opsional)</label>
            <input type="date" name="tgl_cetak" value="{{ date('Y-m-d') }}" class="form-input-premium">
            <p class="text-[10px] text-slate-400 mt-1">Jika dikosongkan akan menggunakan tanggal hari ini.</p>
        </div>

        <!-- Tabel Pilihan PPTK -->
        <div class="border border-slate-200 rounded-2xl overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary">
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Nama PPTK</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">NIP</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Jabatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pptks as $pptk)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-center">
                            <input type="checkbox" name="pptk_ids[]" value="{{ $pptk->id }}" class="pptk-checkbox w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary">
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $pptk->nama }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $pptk->nip }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $pptk->jabatan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">Tidak ada data PPTK. Silakan tambah data di menu Data Pejabat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-end gap-4 mt-8 pt-4 border-t border-slate-100">
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:bg-indigo-700 transition-all flex items-center gap-2">
                <i data-lucide="printer" class="w-5 h-5"></i>
                Cetak Terpilih
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.pptk-checkbox');

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });
        }
    });
</script>
@endpush
@endsection
