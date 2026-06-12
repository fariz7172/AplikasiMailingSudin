const fs = require('fs');
const path = require('path');

const files = [
    'resources/views/payments/create.blade.php',
    'resources/views/payments/edit.blade.php'
];

files.forEach(file => {
    let p = path.join(__dirname, '..', file);
    if (!fs.existsSync(p)) return;
    let content = fs.readFileSync(p, 'utf-8');

    // 1. Update root Alpine x-data
    let alpineStart = `x-data="{ \n            activeStep:`;
    if(content.includes(alpineStart)) {
        let replacement = `x-data="{ 
            isNewVendor: false,
            selectedVendorId: '',
            vendors: @json($vendors),
            vendorData: {
                nama_perusahaan: 'Sudin Sumber Daya Air Kota Administrasi Jakarta Utara',
                direktur: '', npwp: '', akte: '', tgl_akte: '', tdp: '', tgl_tdp: '', bank: '', no_rekening: '', alamat: ''
            },
            onVendorSelect() {
                if(this.selectedVendorId) {
                    let v = this.vendors.find(x => x.id == this.selectedVendorId);
                    if(v) {
                        this.vendorData = {
                            nama_perusahaan: v.nama_perusahaan || '',
                            direktur: v.direktur || '',
                            npwp: v.npwp || '',
                            akte: v.akte || '',
                            tgl_akte: v.tgl_akte ? v.tgl_akte.substring(0,10) : '',
                            tdp: v.tdp || '',
                            tgl_tdp: v.tgl_tdp ? v.tgl_tdp.substring(0,10) : '',
                            bank: v.bank || '',
                            no_rekening: v.no_rekening || '',
                            alamat: v.alamat || ''
                        };
                    }
                } else {
                    this.vendorData = { nama_perusahaan: '', direktur: '', npwp: '', akte: '', tgl_akte: '', tdp: '', tgl_tdp: '', bank: '', no_rekening: '', alamat: '' };
                }
            },
            activeStep:`;
        content = content.replace(alpineStart, replacement);
    }

    // 2. Add vendor data initialization on init() for edit.blade.php
    if (file.includes('edit.blade.php')) {
        let initMatch = `init() {`;
        let initReplacement = `init() {
                // Initialize edit mode vendor data
                let currentVendorId = '{{ $payment->vendor_id }}';
                if(currentVendorId) {
                    this.selectedVendorId = currentVendorId;
                    this.onVendorSelect();
                }`;
        content = content.replace(initMatch, initReplacement);
    }

    // 3. Update HTML for Nama Perusahaan
    let perusahaanHTML = `<label class="form-label-premium">Nama Perusahaan / Vendor (Payer/Payee)</label>
                                <input type="text" name="nama_perusahaan"
                                    value="Sudin Sumber Daya Air Kota Administrasi Jakarta Utara"
                                    class="form-input-premium font-bold">`;
    if (file.includes('edit.blade.php')) {
        perusahaanHTML = `<label class="form-label-premium">Nama Perusahaan / Vendor (Payer/Payee)</label>
                                <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $payment->vendor->nama_perusahaan) }}" class="form-input-premium">`;
    }

    let newPerusahaanHTML = `
                                <div class="mb-4 p-4 bg-primary/5 rounded-xl border border-primary/20 space-y-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" x-model="isNewVendor" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary">
                                        <span class="text-sm font-bold text-slate-700">Buat / Input Vendor Baru</span>
                                    </label>
                                    
                                    <div x-show="!isNewVendor" x-collapse>
                                        <label class="form-label-premium text-primary">Pilih Data Vendor Tersimpan</label>
                                        <select x-model="selectedVendorId" @change="onVendorSelect()" class="form-input-premium font-semibold">
                                            <option value="">-- Pilih Vendor --</option>
                                            <template x-for="v in vendors" :key="v.id">
                                                <option :value="v.id" x-text="v.nama_perusahaan"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <label class="form-label-premium">Nama Perusahaan / Vendor (Payer/Payee)</label>
                                <input type="text" name="nama_perusahaan" x-model="vendorData.nama_perusahaan" :readonly="!isNewVendor" :class="!isNewVendor ? 'bg-slate-100 cursor-not-allowed text-slate-500' : ''" class="form-input-premium font-bold">
    `;
    content = content.replace(perusahaanHTML, newPerusahaanHTML);

    // Helper to replace normal inputs
    function replaceInput(name, isTextarea = false) {
        let pattern = new RegExp(`<input type="text" name="${name}" class="form-input-premium".*?>`, 'g');
        let patternDate = new RegExp(`<input type="date" name="${name}" class="form-input-premium".*?>`, 'g');
        let patternList = new RegExp(`<input type="text" name="${name}" class="form-input-premium" .*?list=".*?">`, 'g');
        let patternEdit = new RegExp(`<input type="text" name="${name}" value="{{ old\\('${name}', .*?\\) }}" class="form-input-premium".*?>`, 'g');
        let patternDateEdit = new RegExp(`<input type="date" name="${name}" value="{{ old\\('${name}', .*?\\) }}" class="form-input-premium".*?>`, 'g');

        let type = name.startsWith('tgl_') ? 'date' : 'text';
        let replaceStr = `<input type="${type}" name="${name}" x-model="vendorData.${name}" :readonly="!isNewVendor" :class="!isNewVendor ? 'bg-slate-100 cursor-not-allowed text-slate-500' : ''" class="form-input-premium">`;
        
        content = content.replace(pattern, replaceStr);
        content = content.replace(patternDate, replaceStr);
        content = content.replace(patternList, replaceStr);
        content = content.replace(patternEdit, replaceStr);
        content = content.replace(patternDateEdit, replaceStr);
    }

    replaceInput('direktur');
    replaceInput('npwp');
    replaceInput('akte');
    replaceInput('tgl_akte');
    replaceInput('tdp');
    replaceInput('tgl_tdp');
    replaceInput('bank');
    replaceInput('no_rekening');
    replaceInput('alamat'); // Wait, alamat is text input, not textarea in create.blade.php

    fs.writeFileSync(p, content, 'utf-8');
    console.log('Modified ' + file);
});
