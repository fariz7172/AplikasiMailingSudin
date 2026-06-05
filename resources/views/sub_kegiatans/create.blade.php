@extends('layouts.admin')

@section('title', 'Tambah Sub Kegiatan')
@section('page_title', 'Tambah Master Sub Kegiatan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Header --}}
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center">
                <i data-lucide="git-branch" class="w-6 h-6"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-800">Tambah Sub Kegiatan Baru</h2>
                <p class="text-xs text-slate-400">Sub Kegiatan berada di bawah Kegiatan → Program</p>
            </div>
        </div>

        {{-- Hierarki Visualisasi --}}
        <div class="px-8 pt-6">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 bg-slate-50 px-4 py-3 rounded-xl border border-slate-100">
                <i data-lucide="folder" class="w-4 h-4 text-primary"></i>
                <span class="text-primary">Program</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <i data-lucide="layers" class="w-4 h-4 text-accent"></i>
                <span class="text-accent">Kegiatan</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <i data-lucide="git-branch" class="w-4 h-4 text-indigo-500"></i>
                <span class="text-indigo-600 font-black">Sub Kegiatan ← Anda di sini</span>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('sub-kegiatans.store') }}" method="POST" class="p-8 space-y-6"
            x-data="subKegiatanForm()" x-init="init()">
            @csrf

            {{-- Step 1: Pilih Program --}}
            <div>
                <label class="form-label-premium">Program Induk <span class="text-red-400">*</span></label>
                <select name="" x-model="selectedProgramId" @change="fetchKegiatan()"
                    class="form-input-premium">
                    <option value="">— Pilih Program —</option>
                    @foreach($programs as $prog)
                    <option value="{{ $prog->id }}"
                        {{ (old('_program_id') ?? request('program_id')) == $prog->id ? 'selected' : '' }}>
                        {{ $prog->kode }} - {{ $prog->nama }} {{ $prog->tahun_anggaran ? '('.$prog->tahun_anggaran.')' : '' }}
                    </option>
                    @endforeach
                </select>
                @if($programs->isEmpty())
                <p class="mt-1 text-xs text-amber-600 font-semibold">
                    ⚠ Buat <a href="{{ route('programs.create') }}" class="text-primary hover:underline">Program</a> terlebih dahulu.
                </p>
                @endif
            </div>

            {{-- Step 2: Pilih Kegiatan (cascade dari Program) --}}
            <div>
                <label class="form-label-premium">
                    Kegiatan Induk <span class="text-red-400">*</span>
                    <span x-show="loading" class="ml-1 text-slate-400 normal-case font-normal text-[10px]">(memuat...)</span>
                </label>
                <select name="kegiatan_id"
                    :disabled="kegiatanOptions.length === 0 || loading"
                    :class="kegiatanOptions.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                    class="form-input-premium @error('kegiatan_id') border-red-400 @enderror">
                    <option value="">— Pilih Kegiatan —</option>
                    @foreach($kegiatans as $keg)
                    <option value="{{ $keg->id }}" {{ old('kegiatan_id', $selectedKegiatanId) == $keg->id ? 'selected' : '' }}>
                      {{ $keg->kode }} - {{ $keg->nama }}
                    </option>
                    @endforeach
                    <template x-for="item in kegiatanOptions" :key="item.id">
                        <option :value="item.id" x-text="(item.kode ? item.kode + ' - ' : '') + item.nama"></option>
                    </template>
                </select>
                @error('kegiatan_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                <p x-show="!selectedProgramId && {{ $kegiatans->isEmpty() ? 'true' : 'false' }}"
                    class="mt-1 text-[10px] text-slate-400 font-semibold">⬆ Pilih Program terlebih dahulu</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label-premium">Kode Sub Kegiatan <span class="text-slate-300 font-normal normal-case">(opsional)</span></label>
                    <input type="text" name="kode" value="{{ old('kode') }}"
                        placeholder="Contoh: 1.03.02.01.001"
                        class="form-input-premium @error('kode') border-red-400 @enderror">
                    @error('kode')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label-premium">Kode Rekening <span class="text-slate-300 font-normal normal-case">(opsional)</span></label>
                    <input type="text" name="kode_rek" value="{{ old('kode_rek') }}"
                        placeholder="Contoh: 5.2.2.01.01"
                        class="form-input-premium @error('kode_rek') border-red-400 @enderror">
                    @error('kode_rek')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label-premium">Nama Sub Kegiatan <span class="text-red-400">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    placeholder="Contoh: Normalisasi Sungai Cilincing Segmen 1"
                    class="form-input-premium @error('nama') border-red-400 @enderror">
                @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label-premium">Keterangan <span class="text-slate-300 font-normal normal-case">(opsional)</span></label>
                <textarea name="keterangan" rows="2"
                    class="form-input-premium resize-none">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-8 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:bg-indigo-700 transition-all flex items-center gap-2 text-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Sub Kegiatan
                </button>
                <a href="{{ route('sub-kegiatans.index') }}"
                    class="px-6 py-3 text-slate-500 font-bold hover:bg-slate-100 rounded-2xl transition-all text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function subKegiatanForm() {
        return {
            selectedProgramId: '{{ old('_program_id', request('program_id', '')) }}',
            kegiatanOptions: [],
            loading: false,

            init() {
                if (this.selectedProgramId) {
                    this.fetchKegiatan();
                }
            },

            fetchKegiatan() {
                if (!this.selectedProgramId) {
                    this.kegiatanOptions = [];
                    return;
                }
                this.loading = true;
                fetch(`/api/kegiatans?program_id=${this.selectedProgramId}`)
                    .then(r => r.json())
                    .then(data => { this.kegiatanOptions = data; })
                    .catch(() => { this.kegiatanOptions = []; })
                    .finally(() => { this.loading = false; });
            }
        };
    }
</script>
@endpush
