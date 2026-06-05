@extends('layouts.admin')

@section('title', 'Tambah Data Lengkap')
@section('page_title', 'Input Transaksi & Kontrak (Full Data)')

@section('content')
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-600 px-6 py-4 rounded-3xl mb-8 flex items-start gap-3 shadow-sm animate-shake">
            <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5"></i>
            <div>
                <p class="font-bold text-sm">Gagal Menyimpan: Terdapat data yang tidak valid atau belum diisi.</p>
                <ul class="list-disc list-inside text-xs mt-1 opacity-80">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <form id="paymentForm" action="{{ route('payments.store') }}" method="POST" class="space-y-8 pb-20" novalidate>
        @csrf

        <div x-data="{ 
            activeStep: parseInt(localStorage.getItem('activePaymentStep')) || 1,
            init() {
                this.$watch('activeStep', value => localStorage.setItem('activePaymentStep', value));
                
                // Otomatis buka step yang memiliki error validasi dari server
                @if($errors->any())
                    const firstError = document.querySelector('.border-rose-500');
                    if (firstError) {
                        const section = firstError.closest('[x-show*="activeStep"]');
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
                    <div class="p-8 space-y-6" x-data="cascadeDropdown()">
                        <!-- Cascade Dropdown 3 Level: Program → Kegiatan → Sub Kegiatan -->
                        <div class="p-5 bg-primary/5 rounded-2xl border border-primary/10 space-y-4">
                            <p class="text-xs font-black text-primary uppercase tracking-wider flex items-center gap-2">
                                <i data-lucide="git-branch" class="w-3.5 h-3.5"></i>
                                Klasifikasi Anggaran (3 Level)
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Level 1: Program --}}
                                <div>
                                    <label class="form-label-premium text-primary">Program</label>
                                    <select id="program_dropdown" x-model="selectedProgramId" @change="fetchKegiatan()"
                                        class="form-input-premium font-semibold">
                                        <option value="">— Pilih Program —</option>
                                        @foreach($programs as $prog)
                                        <option value="{{ $prog->id }}" {{ old('program_id') == $prog->id ? 'selected' : '' }}>
                                          {{ $prog->kode }}  {{ $prog->nama }} {{ $prog->tahun_anggaran ? '('.$prog->tahun_anggaran.')' : '' }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="program_id" :value="selectedProgramId">
                                    <input type="hidden" name="program" :value="selectedProgramName">
                                    <textarea readonly x-show="selectedProgramId" x-text="selectedProgramName" class="form-input-premium mt-2 text-sm text-slate-600 bg-slate-50 resize-none border-dashed" rows="3"></textarea>
                                </div>
                                {{-- Level 2: Kegiatan --}}
                                <div>
                                    <label class="form-label-premium text-accent flex justify-between items-center">
                                        <span>
                                            Kegiatan
                                            <span x-show="loadingKegiatan" class="ml-1 text-slate-400 normal-case font-normal">(memuat...)</span>
                                        </span>
                                        <button type="button" @click="showModalKegiatan = true" x-show="selectedProgramId" class="text-[10px] bg-accent/10 text-accent px-2 py-0.5 rounded font-bold hover:bg-accent/20 transition-colors">+ Tambah Baru</button>
                                    </label>
                                    <select id="kegiatan_dropdown" x-model="selectedKegiatanId" @change="fetchSubKegiatan()"
                                        :disabled="kegiatanOptions.length === 0 || loadingKegiatan"
                                        :class="kegiatanOptions.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                                        class="form-input-premium">
                                        <option value="">— Pilih Kegiatan —</option>
                                        <template x-for="item in kegiatanOptions" :key="item.id">
                                            <option :value="item.id" x-text="item.kode ? item.kode : item.nama"></option>
                                        </template>
                                    </select>
                                    <input type="hidden" name="kegiatan_id" :value="selectedKegiatanId">
                                    <input type="hidden" name="kegiatan" :value="selectedKegiatanName">
                                    <p x-show="!selectedProgramId" class="mt-1 text-[10px] text-slate-400 font-semibold">⬆ Pilih Program dulu</p>
                                    <textarea readonly x-show="selectedKegiatanId" x-text="selectedKegiatanName" class="form-input-premium mt-2 text-sm text-slate-600 bg-slate-50 resize-none border-dashed" rows="3"></textarea>
                                </div>

                                {{-- Modal Tambah Kegiatan --}}
                                <div x-show="showModalKegiatan" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;" x-transition>
                                    <div @click.away="showModalKegiatan = false" class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl relative">
                                        <button type="button" @click="showModalKegiatan = false" class="absolute top-4 right-4 text-slate-400 hover:text-rose-500">
                                            <i data-lucide="x" class="w-5 h-5"></i>
                                        </button>
                                        <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                                            <i data-lucide="plus-circle" class="w-5 h-5 text-accent"></i> Tambah Kegiatan Baru
                                        </h3>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <label class="form-label-premium text-xs">Kode Kegiatan</label>
                                                <input type="text" x-model="newKegiatanKode" class="form-input-premium text-sm" placeholder="Contoh: 1.01">
                                            </div>
                                            <div>
                                                <label class="form-label-premium text-xs">Nama Kegiatan <span class="text-rose-500">*</span></label>
                                                <input type="text" x-model="newKegiatanNama" class="form-input-premium text-sm" placeholder="Nama Kegiatan">
                                            </div>
                                            <div class="pt-2 flex justify-end gap-3">
                                                <button type="button" @click="showModalKegiatan = false" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-100 rounded-xl text-sm transition-all">Batal</button>
                                                <button type="button" @click="saveNewKegiatan()" :disabled="isSavingKegiatan || !newKegiatanNama" class="px-6 py-2 bg-accent text-white font-bold rounded-xl shadow-lg shadow-accent/30 hover:bg-accent/80 disabled:opacity-50 text-sm transition-all flex items-center gap-2">
                                                    <span x-show="isSavingKegiatan" class="animate-spin text-white">⏳</span>
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Level 3: Sub Kegiatan --}}
                                <div>
                                    <label class="form-label-premium text-indigo-500 flex justify-between items-center">
                                        <span>
                                            Sub Kegiatan
                                            <span x-show="loadingSubKegiatan" class="ml-1 text-slate-400 normal-case font-normal">(memuat...)</span>
                                        </span>
                                        <button type="button" @click="showModalSubKegiatan = true" x-show="selectedKegiatanId" class="text-[10px] bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded font-bold hover:bg-indigo-200 transition-colors">+ Tambah Baru</button>
                                    </label>
                                    <select id="sub_kegiatan_dropdown" x-model="selectedSubKegiatanId" @change="onSubKegiatanChange()"
                                        :disabled="subKegiatanOptions.length === 0 || loadingSubKegiatan"
                                        :class="subKegiatanOptions.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                                        class="form-input-premium">
                                        <option value="">— Pilih Sub Kegiatan —</option>
                                        <template x-for="item in subKegiatanOptions" :key="item.id">
                                            <option :value="item.id" x-text="item.kode ? item.kode : item.nama"></option>
                                        </template>
                                    </select>
                                    <input type="hidden" name="sub_kegiatan_id" :value="selectedSubKegiatanId">
                                    <input type="hidden" name="sub_kegiatan" :value="selectedSubKegiatanName">
                                    <p x-show="!selectedKegiatanId" class="mt-1 text-[10px] text-slate-400 font-semibold">⬆ Pilih Kegiatan dulu</p>
                                    <textarea readonly x-show="selectedSubKegiatanId" x-text="selectedSubKegiatanName" class="form-input-premium mt-2 text-sm text-slate-600 bg-slate-50 resize-none border-dashed" rows="3"></textarea>
                                </div>

                                {{-- Modal Tambah Sub Kegiatan --}}
                                <div x-show="showModalSubKegiatan" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;" x-transition>
                                    <div @click.away="showModalSubKegiatan = false" class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl relative">
                                        <button type="button" @click="showModalSubKegiatan = false" class="absolute top-4 right-4 text-slate-400 hover:text-rose-500">
                                            <i data-lucide="x" class="w-5 h-5"></i>
                                        </button>
                                        <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                                            <i data-lucide="plus-circle" class="w-5 h-5 text-indigo-500"></i> Tambah Sub Kegiatan Baru
                                        </h3>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <label class="form-label-premium text-xs">Kode Sub Kegiatan</label>
                                                <input type="text" x-model="newSubKode" class="form-input-premium text-sm" placeholder="Contoh: 1.01.01">
                                            </div>
                                            <div>
                                                <label class="form-label-premium text-xs">Nama Sub Kegiatan <span class="text-rose-500">*</span></label>
                                                <input type="text" x-model="newSubNama" class="form-input-premium text-sm" placeholder="Nama Sub Kegiatan">
                                            </div>
                                            <div class="pt-2 flex justify-end gap-3">
                                                <button type="button" @click="showModalSubKegiatan = false" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-100 rounded-xl text-sm transition-all">Batal</button>
                                                <button type="button" @click="saveNewSubKegiatan()" :disabled="isSavingSubKegiatan || !newSubNama" class="px-6 py-2 bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-600 disabled:opacity-50 text-sm transition-all flex items-center gap-2">
                                                    <span x-show="isSavingSubKegiatan" class="animate-spin text-white">⏳</span>
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label-premium">Nomor SPD</label>
                                <input type="text" name="no_spd" class="form-input-premium" placeholder="Input No. SPD">
                            </div>
                            <div>
                                <label class="form-label-premium">Kode Rekening
                                    <span class="text-[9px] text-slate-400 font-normal normal-case ml-1">(auto-isi dari Sub Kegiatan)</span>
                                </label>
                                <input type="text" name="kode_rek" id="kode_rek_input"
                                    :value="selectedKodeRek || ''"
                                    class="form-input-premium" placeholder="Kode rekening">
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
                                    value="Sudin Sumber Daya Air Kota Administrasi Jakarta Utara"
                                    class="form-input-premium font-bold">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label-premium">Nomor Kontrak</label>
                                <input type="text" name="nomor_kontrak" 
                                    class="form-input-premium @error('nomor_kontrak') border-rose-500 ring-2 ring-rose-500/10 @enderror" 
                                    value="{{ old('nomor_kontrak') }}">
                                @error('nomor_kontrak') <p class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label-premium">Tgl. Kontrak</label>
                                <input type="date" name="tgl_kontrak" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Jumlah Kontrak</label>
                                <input type="number" name="nilai_kontrak" id="nilai_kontrak"
                                    class="form-input-premium font-bold text-emerald-600">
                            </div>
                            <div class="md:col-span-4">
                                <label class="form-label-premium">Terbilang (Kontrak)</label>
                                <input type="text" name="terbilang_kontrak" id="terbilang_kontrak"
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
                                <input type="text" name="direktur" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">NPWP</label>
                                <input type="text" name="npwp" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">No. Akte</label>
                                <input type="text" name="akte" class="form-input-premium" placeholder="Nomor Akte">
                            </div>
                            <div>
                                <label class="form-label-premium">Tgl. Akte</label>
                                <input type="date" name="tgl_akte" class="form-input-premium">
                            </div>

                            <div>
                                <label class="form-label-premium">No. TDP</label>
                                <input type="text" name="tdp" class="form-input-premium" placeholder="Nomor TDP">
                            </div>
                            <div>
                                <label class="form-label-premium">Tgl. TDP</label>
                                <input type="date" name="tgl_tdp" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Nama Bank</label>
                                <input type="text" name="bank" class="form-input-premium" placeholder="Contoh: Bank DKI"
                                    list="bank_list">
                                <datalist id="bank_list">
                                    <option value="Bank Mandiri">
                                    <option value="Bank Rakyat Indonesia (BRI)">
                                    <option value="Bank Negara Indonesia (BNI)">
                                    <option value="Bank Central Asia (BCA)">
                                    <option value="Bank Syariah Indonesia (BSI)">
                                    <option value="Bank DKI">
                                    <option value="Bank Tabungan Negara (BTN)">
                                    <option value="Bank CIMB Niaga">
                                    <option value="Bank Danamon Indonesia">
                                    <option value="Bank Permata">
                                    <option value="Bank Maybank Indonesia">
                                    <option value="Bank Panin">
                                    <option value="Bank OCBC NISP">
                                    <option value="Bank Mega">
                                    <option value="Bank Bukopin">
                                    <option value="Bank BJB">
                                    <option value="Bank Jateng">
                                    <option value="Bank Jatim">
                                    <option value="Bank Kalbar">
                                    <option value="Bank Kalsel">
                                    <option value="Bank Kalteng">
                                    <option value="Bank Kaltara">
                                    <option value="Bank NTB Syariah">
                                    <option value="Bank NTT">
                                    <option value="Bank Papua">
                                    <option value="Bank Riau Kepri">
                                    <option value="Bank Sulselbar">
                                    <option value="Bank Sulteng">
                                    <option value="Bank Sultra">
                                    <option value="Bank SulutGo">
                                    <option value="Bank Sumsel Babel">
                                    <option value="Bank Sumut">
                                    <option value="Bank Muamalat Indonesia">
                                    <option value="Bank Sinarmas">
                                    <option value="Bank Commonwealth">
                                    <option value="Bank Woori Saudara">
                                    <option value="Bank HSBC Indonesia">
                                    <option value="Bank UOB Indonesia">
                                    <option value="Bank Artha Graha Internasional">
                                    <option value="Bank Bumi Arta">
                                    <option value="Bank Ina Perdana">
                                    <option value="Bank Jago">
                                    <option value="Bank Aladin Syariah">
                                    <option value="Bank Neo Commerce">
                                    <option value="Bank Digital BCA (Blu)">
                                </datalist>
                            </div>
                            <div>
                                <label class="form-label-premium">No. Rekening</label>
                                <input type="text" name="no_rekening" class="form-input-premium"
                                    placeholder="Nomor Rekening">
                            </div>

                            <div>
                                <label class="form-label-premium">Jangka Waktu</label>
                                <input type="text" name="jangka_waktu" class="form-input-premium"
                                    placeholder="Misal: 30 Hari">
                            </div>
                            <div class="md:col-span-3">
                                <label class="form-label-premium">Alamat Perusahaan</label>
                                <input type="text" name="alamat" class="form-input-premium">
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
                                <input type="text" name="no_spm" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Tanggal SPM</label>
                                <input type="date" name="tgl_spm" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">No. BAST</label>
                                <input type="text" name="nomor_bast" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Tanggal BAST</label>
                                <input type="date" name="tgl_bast" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Progres (%)</label>
                                <input type="text" name="progres" class="form-input-premium" placeholder="Misal: 100%">
                            </div>
                        </div>

                        <div>
                            <label class="form-label-premium">Keperluan Pembayaran</label>
                            <textarea name="keperluan" rows="3" class="form-input-premium"></textarea>
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
                                <input type="text" name="no_kwi" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">No. SPP</label>
                                <input type="text" name="no_spp" class="form-input-premium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label-premium">Pejabat PPTK</label>
                                <select name="pptk_id" id="pptk_id" class="form-input-premium @error('pptk_id') border-rose-500 @enderror">
                                    <option value="">Pilih PPTK...</option>
                                    @foreach($pptk as $p)
                                        <option value="{{ $p->id }}" {{ old('pptk_id') == $p->id ? 'selected' : '' }} 
                                            data-nip="{{ $p->nip }}" data-jabatan="{{ $p->jabatan }}">{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                                @error('pptk_id') <p class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label-premium">NIK PPTK</label>
                                <input type="text" name="nik" id="nik_pptk" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Jabatan PPTK</label>
                                <input type="text" name="jabatan" id="jabatan_pptk" class="form-input-premium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            @foreach([1, 2, 3, 4, 5] as $i)
                                <div>
                                    <label class="form-label-premium">Tagihan {{ $i }}</label>
                                    <input type="number" name="tagihan_{{ $i }}" class="form-input-premium" value="0">
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
                        <!-- Nilai Kontrak Dasar -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100">
                            <div>
                                <label class="form-label-premium">Nilai Kontrak (Awal)</label>
                                <input type="number" id="nilai_kontrak_awal" class="form-input-premium font-bold text-emerald-600 bg-emerald-50/30" readonly>
                            </div>
                            <div class="flex items-center pt-6">
                                <p class="text-xs text-slate-400 italic">* Nilai kontrak awal sebelum addendum atau denda.</p>
                            </div>
                        </div>

                        <!-- Addendum 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label-premium">Addendum Kontrak 1</label>
                                <input type="text" name="addendum_kontrak" class="form-input-premium"
                                    placeholder="Nomor Addendum 1">
                            </div>
                            <div>
                                <label class="form-label-premium">Tgl. Addendum 1</label>
                                <input type="date" name="tgl_addendum" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Nilai Addendum 1</label>
                                <input type="number" name="nilai_addendum1" class="form-input-premium" value="0">
                            </div>
                        </div>

                        <!-- Addendum 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label-premium">Addendum Kontrak 2</label>
                                <input type="text" name="addendum_kontrak2" class="form-input-premium"
                                    placeholder="Nomor Addendum 2">
                            </div>
                            <div>
                                <label class="form-label-premium">Tgl. Addendum 2</label>
                                <input type="date" name="tgl_addendum2" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Nilai Addendum 2</label>
                                <input type="number" name="nilai_addendum2" class="form-input-premium" value="0">
                            </div>
                        </div>

                        <!-- SP2D & Denda -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-slate-100 pt-6">
                            <div>
                                <label class="form-label-premium">No. SP2D</label>
                                <input type="text" name="no_sp2d" class="form-input-premium" placeholder="Input No. SP2D">
                            </div>
                            <div>
                                <label class="form-label-premium">Tanggal SP2D</label>
                                <input type="date" name="tgl_sp2d" class="form-input-premium">
                            </div>
                            <div>
                                <label class="form-label-premium">Denda</label>
                                <input type="number" name="denda" class="form-input-premium text-rose-600 font-bold"
                                    value="0">
                            </div>
                        </div>

                        <div>
                            <label class="form-label-premium">Alamat Update (Jika ada perubahan)</label>
                            <textarea name="alamat_update" rows="2" class="form-input-premium"
                                placeholder="Input alamat terbaru..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="fixed bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-4 bg-white/80 backdrop-blur-md p-4 rounded-3xl border border-slate-200 shadow-2xl z-50">
            <a href="{{ route('payments.index') }}"
                class="px-8 py-3 text-slate-500 font-bold hover:text-slate-700 transition-colors">Batal</a>
            <button type="submit"
                class="px-10 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                <i data-lucide="save" class="w-5 h-5"></i>
                Simpan Seluruh Data
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // --- Cascade Dropdown 3 Level: Program → Kegiatan → Sub Kegiatan ---
        function cascadeDropdown() {
            return {
                selectedProgramId: '{{ old('program_id', '') }}',
                selectedProgramName: '',
                selectedKegiatanId: '{{ old('kegiatan_id', '') }}',
                selectedKegiatanName: '',
                selectedSubKegiatanId: '{{ old('sub_kegiatan_id', '') }}',
                selectedSubKegiatanName: '',
                selectedKodeRek: '',
                kegiatanOptions: [],
                subKegiatanOptions: [],
                loadingKegiatan: false,
                loadingSubKegiatan: false,

                showModalSubKegiatan: false,
                newSubKode: '',
                newSubNama: '',
                isSavingSubKegiatan: false,

                // State for Modal Tambah Kegiatan
                showModalKegiatan: false,
                newKegiatanKode: '',
                newKegiatanNama: '',
                isSavingKegiatan: false,

                init() {
                    this.$nextTick(() => {
                        const sel = document.getElementById('program_dropdown');
                        if (sel) this.selectedProgramName = sel.options[sel.selectedIndex]?.text || '';
                    });
                    if (this.selectedProgramId) {
                        this.fetchKegiatan(true);
                    }
                },

                fetchKegiatan(preserveSelection = false) {
                    const programSel = document.getElementById('program_dropdown');
                    this.selectedProgramName = programSel?.options[programSel.selectedIndex]?.text || '';

                    if (!this.selectedProgramId) {
                        this.kegiatanOptions = [];
                        this.subKegiatanOptions = [];
                        if (!preserveSelection) {
                            this.selectedKegiatanId = '';
                            this.selectedKegiatanName = '';
                            this.selectedSubKegiatanId = '';
                            this.selectedSubKegiatanName = '';
                            this.selectedKodeRek = '';
                        }
                        return;
                    }

                    this.loadingKegiatan = true;
                    fetch(`/api/kegiatans?program_id=${this.selectedProgramId}`)
                        .then(r => r.json())
                        .then(data => {
                            this.kegiatanOptions = data;
                            if (!preserveSelection) {
                                this.selectedKegiatanId = '';
                                this.selectedKegiatanName = '';
                                this.subKegiatanOptions = [];
                                this.selectedSubKegiatanId = '';
                                this.selectedSubKegiatanName = '';
                                this.selectedKodeRek = '';
                            } else if (this.selectedKegiatanId) {
                                // Lanjut fetch sub kegiatan
                                this.fetchSubKegiatan(true);
                            }
                        })
                        .catch(() => this.kegiatanOptions = [])
                        .finally(() => this.loadingKegiatan = false);
                },

                fetchSubKegiatan(preserveSelection = false) {
                    const foundKeg = this.kegiatanOptions.find(k => k.id == this.selectedKegiatanId);
                    if (foundKeg) {
                        this.selectedKegiatanName = (foundKeg.kode ? foundKeg.kode + ' - ' : '') + foundKeg.nama;
                    } else {
                        const kegSel = document.getElementById('kegiatan_dropdown');
                        this.selectedKegiatanName = kegSel?.options[kegSel.selectedIndex]?.text || '';
                    }

                    if (!this.selectedKegiatanId) {
                        this.subKegiatanOptions = [];
                        if (!preserveSelection) {
                            this.selectedSubKegiatanId = '';
                            this.selectedSubKegiatanName = '';
                            this.selectedKodeRek = '';
                        }
                        return;
                    }

                    this.loadingSubKegiatan = true;
                    fetch(`/api/sub-kegiatans?kegiatan_id=${this.selectedKegiatanId}`)
                        .then(r => r.json())
                        .then(data => {
                            this.subKegiatanOptions = data;
                            if (!preserveSelection) {
                                this.selectedSubKegiatanId = '';
                                this.selectedSubKegiatanName = '';
                                this.selectedKodeRek = '';
                            } else {
                                // Auto-fill jika ada old value
                                const found = data.find(s => s.id == this.selectedSubKegiatanId);
                                if (found) {
                                    this.selectedKodeRek = found.kode_rek || '';
                                    this.selectedSubKegiatanName = (found.kode ? found.kode + ' - ' : '') + found.nama || '';
                                }
                            }
                        })
                        .catch(() => this.subKegiatanOptions = [])
                        .finally(() => this.loadingSubKegiatan = false);
                },

                onSubKegiatanChange() {
                    const found = this.subKegiatanOptions.find(s => s.id == this.selectedSubKegiatanId);
                    if (found) {
                        this.selectedKodeRek = found.kode_rek || '';
                        this.selectedSubKegiatanName = (found.kode ? found.kode + ' - ' : '') + found.nama;
                    } else {
                        this.selectedKodeRek = '';
                        const subSel = document.getElementById('sub_kegiatan_dropdown');
                        this.selectedSubKegiatanName = subSel?.options[subSel.selectedIndex]?.text || '';
                    }
                    // Sync DOM untuk localStorage persistence
                    const rekEl = document.getElementById('kode_rek_input');
                    if (rekEl) { rekEl.value = this.selectedKodeRek; rekEl.dispatchEvent(new Event('input')); }
                },

                // --- Modal Actions ---
                saveNewSubKegiatan() {
                    if (!this.selectedKegiatanId || !this.newSubNama) return;
                    
                    this.isSavingSubKegiatan = true;
                    
                    fetch('/api/sub-kegiatans', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            kegiatan_id: this.selectedKegiatanId,
                            kode: this.newSubKode,
                            nama: this.newSubNama
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal menyimpan sub kegiatan');
                        return response.json();
                    })
                    .then(data => {
                        // Tambahkan data baru ke opsi
                        this.subKegiatanOptions.push(data);
                        // Pilih data yang baru dibuat
                        this.selectedSubKegiatanId = data.id;
                        this.selectedSubKegiatanName = (data.kode ? data.kode + ' - ' : '') + data.nama;
                        this.selectedKodeRek = data.kode_rek || '';
                        
                        // Tutup modal dan reset
                        this.showModalSubKegiatan = false;
                        this.newSubKode = '';
                        this.newSubNama = '';
                        
                        // Trigger re-render AlpineJS
                        this.onSubKegiatanChange();
                    })
                    .catch(err => {
                        alert(err.message);
                    })
                    .finally(() => {
                        this.isSavingSubKegiatan = false;
                    });
                },

                // --- Modal Actions Kegiatan ---
                saveNewKegiatan() {
                    if (!this.selectedProgramId || !this.newKegiatanNama) return;
                    
                    this.isSavingKegiatan = true;
                    
                    fetch('/api/kegiatans', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            program_id: this.selectedProgramId,
                            kode: this.newKegiatanKode,
                            nama: this.newKegiatanNama
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal menyimpan kegiatan');
                        return response.json();
                    })
                    .then(data => {
                        // Tambahkan data baru ke opsi
                        this.kegiatanOptions.push(data);
                        // Pilih data yang baru dibuat
                        this.selectedKegiatanId = data.id;
                        this.selectedKegiatanName = (data.kode ? data.kode + ' - ' : '') + data.nama;
                        
                        // Tutup modal dan reset
                        this.showModalKegiatan = false;
                        this.newKegiatanKode = '';
                        this.newKegiatanNama = '';
                        
                        // Trigger re-render AlpineJS untuk load Sub Kegiatan (yang akan kosong tentunya)
                        this.fetchSubKegiatan();
                    })
                    .catch(err => {
                        alert(err.message);
                    })
                    .finally(() => {
                        this.isSavingKegiatan = false;
                    });
                }
            };
        }
    </script>
@endpush

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
                // Sync ke input di Section VI
                const inputAwal = document.getElementById('nilai_kontrak_awal');
                if (inputAwal) inputAwal.value = val;
            } else {
                document.getElementById('terbilang_kontrak').value = "";
                const inputAwal = document.getElementById('nilai_kontrak_awal');
                if (inputAwal) inputAwal.value = "";
            }
        });

        // --- Fitur Persistence (Auto-Save) ---
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('paymentForm');
            if (!form) return;
            const storageKey = 'payment_create_form_data';

            // 1. Load data dari LocalStorage
            const savedData = JSON.parse(localStorage.getItem(storageKey));
            if (savedData) {
                Object.keys(savedData).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        input.value = savedData[key];
                        // Trigger event input agar fungsi 'terbilang' atau lainnya tetap jalan
                        input.dispatchEvent(new Event('input'));
                    }
                });
            }

            // 2. Simpan data setiap ada perubahan input
            form.addEventListener('input', function() {
                const data = {};
                Array.from(form.elements).forEach(el => {
                    if (el.name) {
                        data[el.name] = el.value;
                    }
                });
                localStorage.setItem(storageKey, JSON.stringify(data));
            });

            // 3. Hapus data saat form di-submit
            form.addEventListener('submit', function(e) {
                // Validasi manual sebelum submit karena kita menggunakan 'novalidate'
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();

                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        // Cari kontainer seksi (accordion) yang berisi input invalid ini
                        const section = firstInvalid.closest('[x-show*="activeStep"]');
                        if (section) {
                            const stepMatch = section.getAttribute('x-show').match(/activeStep === (\d+)/);
                            if (stepMatch) {
                                // Update activeStep di Alpine.js secara manual via DOM dispatch
                                // Atau cara termudah: cari header seksi tersebut dan klik
                                const stepNum = stepMatch[1];
                                const headers = document.querySelectorAll('[\\@click*="activeStep"]');
                                headers.forEach(header => {
                                    if (header.getAttribute('@click').includes(`activeStep === ${stepNum}`) || 
                                        header.getAttribute('@click').includes(`${stepNum} : ${stepNum}`)) {
                                        header.click();
                                    }
                                });
                            }
                        }
                        
                        // Beri waktu sedikit untuk animasi slideDown accordion
                        setTimeout(() => {
                            firstInvalid.focus();
                            firstInvalid.classList.add('ring-2', 'ring-rose-500', 'border-rose-500');
                        }, 400);
                    }
                    return;
                }

                localStorage.removeItem(storageKey);
                localStorage.removeItem('activePaymentStep');
            });
        });

        // --- Auto-fill PPTK Data ---
        document.getElementById('pptk_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const nip = selectedOption.getAttribute('data-nip') || '';
            const jabatan = selectedOption.getAttribute('data-jabatan') || '';
            
            document.getElementById('nik_pptk').value = nip;
            document.getElementById('jabatan_pptk').value = jabatan;
            
            // Trigger input event for persistence save
            document.getElementById('nik_pptk').dispatchEvent(new Event('input'));
            document.getElementById('jabatan_pptk').dispatchEvent(new Event('input'));
        });
    </script>
@endpush