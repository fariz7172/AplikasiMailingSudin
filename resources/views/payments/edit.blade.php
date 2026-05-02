@extends('layouts.admin')

@section('title', 'Edit Data Lengkap')
@section('page_title', 'Perbarui Transaksi & Kontrak')

@section('content')
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-600 px-6 py-4 rounded-3xl mb-8 flex items-start gap-3 shadow-sm animate-shake">
            <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5"></i>
            <div>
                <p class="font-bold text-sm">Gagal Memperbarui: Terdapat data yang tidak valid.</p>
                <ul class="list-disc list-inside text-xs mt-1 opacity-80">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form id="paymentForm" action="{{ route('payments.update', $payment->id) }}" method="POST" class="space-y-8 pb-20" novalidate>
        @csrf
        @method('PUT')

        <!-- Hidden IDs for updating -->
        <input type="hidden" name="vendor_id" value="{{ $payment->vendor_id }}">
        <input type="hidden" name="contract_id" value="{{ $payment->contract_id }}">

        <div x-data="{ 
            activeStep: 1,
            init() {
                // Jika ada error dari server, buka step yang memiliki error tersebut
                @if($errors->any())
                    const firstError = document.querySelector('.border-rose-500');
                    if (firstError) {
                        const section = firstError.closest('[x-show*=&quot;activeStep&quot;]');
                        if (section) {
                            const stepMatch = section.getAttribute('x-show').match(/activeStep === (\d+)/);
                            if (stepMatch) {
                                this.activeStep = parseInt(stepMatch[1]);
                            }
                        }
                    }
                @endif
            }
        }" class="space-y-6 pb-20">
            
            <!-- I. DATA ANGGARAN & PROGRAM -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300"
                :class="activeStep === 1 ? 'ring-2 ring-primary/20 border-primary/20' : ''">
                <div @click="activeStep = activeStep === 1 ? 0 : 1"
                    class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center">
                            <i data-lucide="component" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-slate-700">I. Data Anggaran & Program</h3>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300"
                        :class="activeStep === 1 ? 'rotate-180' : ''"></i>
                </div>
                <div x-show="activeStep === 1" x-collapse x-cloak>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label-premium">Nomor SPD</label>
                                <input type="text" name="no_spd" value="{{ old('no_spd', $payment->no_spd) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Program</label>
                                <input type="text" name="program" value="{{ old('program', $payment->program) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Kode Rekening</label>
                                <input type="text" name="kode_rek" value="{{ old('kode_rek', $payment->kode_rek) }}" class="form-input-premium">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label-premium">Kegiatan</label>
                                <textarea name="kegiatan" rows="2" class="form-input-premium">{{ old('kegiatan', $payment->kegiatan) }}</textarea>
                            </div>
                            <div>
                                <label class="form-label-premium">Sub Kegiatan</label>
                                <textarea name="sub_kegiatan" rows="2" class="form-input-premium">{{ old('sub_kegiatan', $payment->sub_kegiatan) }}</textarea>
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="button" @click="activeStep = 2"
                                class="px-6 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center gap-2">
                                Selanjutnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- II. RINCIAN KONTRAK & TERBILANG -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300"
                :class="activeStep === 2 ? 'ring-2 ring-emerald-500/20 border-emerald-500/20' : ''">
                <div @click="activeStep = activeStep === 2 ? 0 : 2"
                    class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-500 text-white rounded-lg flex items-center justify-center">
                            <i data-lucide="file-check" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-slate-700">II. Rincian Kontrak</h3>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300"
                        :class="activeStep === 2 ? 'rotate-180' : ''"></i>
                </div>
                <div x-show="activeStep === 2" x-collapse x-cloak>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="md:col-span-4">
                                <label class="form-label-premium">Nama Perusahaan / Vendor (Payer/Payee)</label>
                                <input type="text" name="nama_perusahaan"
                                    value="{{ old('nama_perusahaan', $payment->vendor->nama_perusahaan) }}"
                                    class="form-input-premium font-bold">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label-premium">Nomor Kontrak</label>
                                <input type="text" name="nomor_kontrak" 
                                    class="form-input-premium @error('nomor_kontrak') border-rose-500 ring-2 ring-rose-500/10 @enderror" 
                                    value="{{ old('nomor_kontrak', $payment->contract->nomor_kontrak) }}">
                                @error('nomor_kontrak') <p class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label-premium">Tgl. Kontrak</label>
                                <input type="date" name="tgl_kontrak" value="{{ old('tgl_kontrak', $payment->contract->tgl_kontrak) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Jumlah Kontrak</label>
                                <input type="number" name="nilai_kontrak" id="nilai_kontrak"
                                    value="{{ old('nilai_kontrak', $payment->contract->nilai_kontrak) }}"
                                    class="form-input-premium font-bold text-emerald-600">
                            </div>
                            <div class="md:col-span-4">
                                <label class="form-label-premium">Terbilang (Kontrak)</label>
                                <input type="text" name="terbilang_kontrak" id="terbilang_kontrak"
                                    value="{{ old('terbilang_kontrak', $payment->contract->terbilang_kontrak) }}"
                                    class="form-input-premium italic text-slate-500 bg-slate-50" readonly>
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="button" @click="activeStep = 3"
                                class="px-6 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center gap-2">
                                Selanjutnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- III. DATA VENDOR & LEGALITAS -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300"
                :class="activeStep === 3 ? 'ring-2 ring-indigo-500/20 border-indigo-500/20' : ''">
                <div @click="activeStep = activeStep === 3 ? 0 : 3"
                    class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-indigo-500 text-white rounded-lg flex items-center justify-center">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-slate-700">III. Data Vendor & Legalitas</h3>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300"
                        :class="activeStep === 3 ? 'rotate-180' : ''"></i>
                </div>
                <div x-show="activeStep === 3" x-collapse x-cloak>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label class="form-label-premium">Nama Direktur</label>
                                <input type="text" name="direktur" value="{{ old('direktur', $payment->vendor->direktur) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">NPWP</label>
                                <input type="text" name="npwp" value="{{ old('npwp', $payment->vendor->npwp) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">No. Akte</label>
                                <input type="text" name="akte" value="{{ old('akte', $payment->vendor->akte) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Tgl. Akte</label>
                                <input type="date" name="tgl_akte" value="{{ old('tgl_akte', $payment->vendor->tgl_akte) }}" class="form-input-premium">
                            </div>

                            <div>
                                <label class="form-label-premium">No. TDP</label>
                                <input type="text" name="tdp" value="{{ old('tdp', $payment->vendor->tdp) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Tgl. TDP</label>
                                <input type="date" name="tgl_tdp" value="{{ old('tgl_tdp', $payment->vendor->tgl_tdp) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Nama Bank</label>
                                <input type="text" name="bank" value="{{ old('bank', $payment->vendor->bank) }}" class="form-input-premium" list="bank_list">
                            </div>
                            <div>
                                <label class="form-label-premium">No. Rekening</label>
                                <input type="text" name="no_rekening" value="{{ old('no_rekening', $payment->vendor->no_rekening) }}" class="form-input-premium">
                            </div>

                            <div>
                                <label class="form-label-premium">Jangka Waktu</label>
                                <input type="text" name="jangka_waktu" value="{{ old('jangka_waktu', $payment->contract->jangka_waktu) }}" class="form-input-premium">
                            </div>
                            <div class="md:col-span-3">
                                <label class="form-label-premium">Alamat Perusahaan</label>
                                <input type="text" name="alamat" value="{{ old('alamat', $payment->vendor->alamat) }}" class="form-input-premium">
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="button" @click="activeStep = 4"
                                class="px-6 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center gap-2">
                                Selanjutnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IV. DOKUMEN SPM & BAST -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300"
                :class="activeStep === 4 ? 'ring-2 ring-amber-500/20 border-amber-500/20' : ''">
                <div @click="activeStep = activeStep === 4 ? 0 : 4"
                    class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-amber-500 text-white rounded-lg flex items-center justify-center">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-slate-700">IV. Dokumen SPM & BAST</h3>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300"
                        :class="activeStep === 4 ? 'rotate-180' : ''"></i>
                </div>
                <div x-show="activeStep === 4" x-collapse x-cloak>
                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label class="form-label-premium">No. SPM</label>
                                <input type="text" name="no_spm" value="{{ old('no_spm', $payment->no_spm) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Tanggal SPM</label>
                                <input type="date" name="tgl_spm" value="{{ old('tgl_spm', $payment->tgl_spm?->format('Y-m-d')) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">No. BAST</label>
                                <input type="text" name="nomor_bast" value="{{ old('nomor_bast', $payment->nomor_bast) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Tanggal BAST</label>
                                <input type="date" name="tgl_bast" value="{{ old('tgl_bast', $payment->tgl_bast?->format('Y-m-d')) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Progres (%)</label>
                                <input type="text" name="progres" value="{{ old('progres', $payment->progres) }}" class="form-input-premium">
                            </div>
                        </div>

                        <div>
                            <label class="form-label-premium">Keperluan Pembayaran</label>
                            <textarea name="keperluan" rows="3" class="form-input-premium">{{ old('keperluan', $payment->keperluan) }}</textarea>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="button" @click="activeStep = 5"
                                class="px-6 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center gap-2">
                                Selanjutnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- V. RINCIAN TEKNIS & TAGIHAN -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300"
                :class="activeStep === 5 ? 'ring-2 ring-rose-500/20 border-rose-500/20' : ''">
                <div @click="activeStep = activeStep === 5 ? 0 : 5"
                    class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-rose-500 text-white rounded-lg flex items-center justify-center">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-slate-700">V. Rincian Teknis & Tagihan</h3>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300"
                        :class="activeStep === 5 ? 'rotate-180' : ''"></i>
                </div>
                <div x-show="activeStep === 5" x-collapse x-cloak>
                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label-premium">No. KWI</label>
                                <input type="text" name="no_kwi" value="{{ old('no_kwi', $payment->no_kwi) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">No. SPP</label>
                                <input type="text" name="no_spp" value="{{ old('no_spp', $payment->no_spp) }}" class="form-input-premium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label-premium">Pejabat PPTK</label>
                                <select name="pptk_id" id="pptk_id" class="form-input-premium @error('pptk_id') border-rose-500 @enderror">
                                    <option value="">Pilih PPTK...</option>
                                    @foreach($pptk as $p)
                                        <option value="{{ $p->id }}" {{ old('pptk_id', $payment->pptk_id) == $p->id ? 'selected' : '' }} 
                                            data-nip="{{ $p->nip }}" data-jabatan="{{ $p->jabatan }}">{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pptk_id') <p class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label-premium">NIK PPTK</label>
                                <input type="text" name="nik" id="nik_pptk" value="{{ old('nik', $payment->nik) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Jabatan PPTK</label>
                                <input type="text" name="jabatan" id="jabatan_pptk" value="{{ old('jabatan', $payment->jabatan) }}" class="form-input-premium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            @foreach([1, 2, 3, 4, 5] as $i)
                                <div>
                                    <label class="form-label-premium">Tagihan {{ $i }}</label>
                                    @php $fieldName = "tagihan_$i"; @endphp
                                    <input type="number" name="tagihan_{{ $i }}" class="form-input-premium" value="{{ old($fieldName, $payment->$fieldName) }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="button" @click="activeStep = 6"
                                class="px-6 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all flex items-center gap-2">
                                Selanjutnya <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VI. ADDENDUM, DENDA & PEMBARUAN -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300"
                :class="activeStep === 6 ? 'ring-2 ring-slate-700/20 border-slate-700/20' : ''">
                <div @click="activeStep = activeStep === 6 ? 0 : 6"
                    class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-slate-700 text-white rounded-lg flex items-center justify-center">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-slate-700">VI. Addendum, Denda & Pembaruan</h3>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-300"
                        :class="activeStep === 6 ? 'rotate-180' : ''"></i>
                </div>
                <div x-show="activeStep === 6" x-collapse x-cloak>
                    <div class="p-8 space-y-8">
                        <!-- Addendum 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label-premium">Addendum Kontrak 1</label>
                                <input type="text" name="addendum_kontrak" value="{{ old('addendum_kontrak', $payment->contract->addendum_kontrak) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Tgl. Addendum 1</label>
                                <input type="date" name="tgl_addendum" value="{{ old('tgl_addendum', $payment->contract->tgl_addendum) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Nilai Addendum 1</label>
                                <input type="number" name="nilai_addendum1" value="{{ old('nilai_addendum1', $payment->contract->nilai_addendum1) }}" class="form-input-premium">
                            </div>
                        </div>

                        <!-- Addendum 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label-premium">Addendum Kontrak 2</label>
                                <input type="text" name="addendum_kontrak2" value="{{ old('addendum_kontrak2', $payment->contract->addendum_kontrak2) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Tgl. Addendum 2</label>
                                <input type="date" name="tgl_addendum2" value="{{ old('tgl_addendum2', $payment->contract->tgl_addendum2) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Nilai Addendum 2</label>
                                <input type="number" name="nilai_addendum2" value="{{ old('nilai_addendum2', $payment->contract->nilai_addendum2) }}" class="form-input-premium">
                            </div>
                        </div>

                        <!-- SP2D & Denda -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-slate-100 pt-6">
                            <div>
                                <label class="form-label-premium">No. SP2D</label>
                                <input type="text" name="no_sp2d" value="{{ old('no_sp2d', $payment->no_sp2d) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Tanggal SP2D</label>
                                <input type="date" name="tgl_sp2d" value="{{ old('tgl_sp2d', $payment->tgl_sp2d?->format('Y-m-d')) }}" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Denda</label>
                                <input type="number" name="denda" value="{{ old('denda', $payment->denda) }}" class="form-input-premium text-rose-600 font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="form-label-premium">Alamat Update (Jika ada perubahan)</label>
                            <textarea name="alamat_update" rows="2" class="form-input-premium">{{ old('alamat_update', $payment->vendor->alamat_update) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-4 bg-white/80 backdrop-blur-md p-4 rounded-3xl border border-slate-200 shadow-2xl z-50">
            <a href="{{ route('payments.index') }}" class="px-8 py-3 text-slate-500 font-bold hover:text-slate-700 transition-colors">Batal</a>
            <button type="submit" class="px-10 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                Perbarui Seluruh Data
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function terbilang(angka) {
            angka = Math.floor(angka);
            var suar = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            var temp = "";
            if (angka < 12) {
                temp = " " + suar[angka];
            } else if (angka < 20) {
                temp = terbilang(angka - 10) + " Belas";
            } else if (angka < 100) {
                temp = terbilang(Math.floor(angka / 10)) + " Puluh" + terbilang(angka % 10);
            } else if (angka < 200) {
                temp = " Seratus" + terbilang(angka - 100);
            } else if (angka < 1000) {
                temp = terbilang(Math.floor(angka / 100)) + " Ratus" + terbilang(angka % 100);
            } else if (angka < 2000) {
                temp = " Seribu" + terbilang(angka - 1000);
            } else if (angka < 1000000) {
                temp = terbilang(Math.floor(angka / 1000)) + " Ribu" + terbilang(angka % 1000);
            } else if (angka < 1000000000) {
                temp = terbilang(Math.floor(angka / 1000000)) + " Juta" + terbilang(angka % 1000000);
            } else if (angka < 1000000000000) {
                temp = terbilang(Math.floor(angka / 1000000000)) + " Miliar" + terbilang(angka % 1000000000);
            }
            return temp;
        }

        document.getElementById('nilai_kontrak').addEventListener('input', function () {
            let val = this.value;
            if (val) {
                let hasil = terbilang(val).trim() + " Rupiah";
                document.getElementById('terbilang_kontrak').value = hasil;
            } else {
                document.getElementById('terbilang_kontrak').value = "";
            }
        });

        // Trigger terbilang on load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nilai_kontrak').dispatchEvent(new Event('input'));
        });

        // --- Auto-fill PPTK Data ---
        document.getElementById('pptk_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const nip = selectedOption.getAttribute('data-nip') || '';
            const jabatan = selectedOption.getAttribute('data-jabatan') || '';
            
            document.getElementById('nik_pptk').value = nip;
            document.getElementById('jabatan_pptk').value = jabatan;
        });
    </script>
@endpush
