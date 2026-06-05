@extends('layouts.admin')

@section('title', 'Edit Sub Kegiatan')
@section('page_title', 'Edit Master Sub Kegiatan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Header --}}
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="edit-3" class="w-6 h-6"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-800">Edit Sub Kegiatan</h2>
                <p class="text-xs text-slate-400">Perbarui: <span class="font-bold text-slate-600">{{ $subKegiatan->nama }}</span></p>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('sub-kegiatans.update', $subKegiatan->id) }}" method="POST"
            class="p-8 space-y-6" x-data="subKegiatanEditForm()" x-init="init()">
            @csrf @method('PUT')

            {{-- Program --}}
            <div>
                <label class="form-label-premium">Program Induk</label>
                <select x-model="selectedProgramId" @change="fetchKegiatan()" class="form-input-premium">
                    <option value="">— Pilih Program —</option>
                    @foreach($programs as $prog)
                    <option value="{{ $prog->id }}"
                        {{ $subKegiatan->kegiatan->program_id == $prog->id ? 'selected' : '' }}>
                        {{ $prog->nama }} {{ $prog->tahun_anggaran ? '('.$prog->tahun_anggaran.')' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Kegiatan --}}
            <div>
                <label class="form-label-premium">
                    Kegiatan Induk <span class="text-red-400">*</span>
                    <span x-show="loading" class="ml-1 text-slate-400 normal-case font-normal text-[10px]">(memuat...)</span>
                </label>
                <select name="kegiatan_id"
                    :disabled="loading"
                    class="form-input-premium @error('kegiatan_id') border-red-400 @enderror">
                    {{-- Opsi bawaan (dari DB saat load) --}}
                    @foreach($kegiatans as $keg)
                    <option value="{{ $keg->id }}" {{ $subKegiatan->kegiatan_id == $keg->id ? 'selected' : '' }}>
                        {{ $keg->nama }}
                    </option>
                    @endforeach
                    {{-- Opsi dari fetch (jika ganti program) --}}
                    <template x-if="kegiatanOptions.length > 0">
                        <template x-for="item in kegiatanOptions" :key="item.id">
                            <option :value="item.id" x-text="(item.kode ? item.kode + ' - ' : '') + item.nama"></option>
                        </template>
                    </template>
                </select>
                @error('kegiatan_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label-premium">Kode Sub Kegiatan</label>
                    <input type="text" name="kode" value="{{ old('kode', $subKegiatan->kode) }}"
                        class="form-input-premium @error('kode') border-red-400 @enderror">
                    @error('kode')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label-premium">Kode Rekening</label>
                    <input type="text" name="kode_rek" value="{{ old('kode_rek', $subKegiatan->kode_rek) }}"
                        class="form-input-premium @error('kode_rek') border-red-400 @enderror">
                    @error('kode_rek')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label-premium">Nama Sub Kegiatan <span class="text-red-400">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $subKegiatan->nama) }}"
                    class="form-input-premium @error('nama') border-red-400 @enderror">
                @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label-premium">Keterangan</label>
                <textarea name="keterangan" rows="2"
                    class="form-input-premium resize-none">{{ old('keterangan', $subKegiatan->keterangan) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-8 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/20 hover:bg-indigo-700 transition-all flex items-center gap-2 text-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Perbarui
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
    function subKegiatanEditForm() {
        return {
            selectedProgramId: '{{ $subKegiatan->kegiatan->program_id }}',
            kegiatanOptions: [],
            loading: false,
            init() {}, // Bawaan sudah di-render server-side

            fetchKegiatan() {
                if (!this.selectedProgramId) { this.kegiatanOptions = []; return; }
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
