<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Dokumen SPP & SPM - {{ $payment->no_spm }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; margin: 0; padding: 0; }
        .font-serif { font-family: 'Times New Roman', Times, serif; }
        @page { size: A4; margin: 0; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-container { padding: 0 !important; margin: 0 !important; }
            .page-break { page-break-before: always; }
            .print-area { 
                box-shadow: none !important; 
                border: none !important; 
                margin: 0 !important; 
                padding: 1.2cm !important;
                width: 210mm !important;
                height: 297mm !important;
                overflow: hidden;
            }
        }
        .print-area {
            background: white;
            width: 210mm;
            min-height: 297mm;
            padding: 1.2cm;
            margin: 20px auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
        }
        [contenteditable="true" data-eid="1"]:focus { outline: 2px solid #3b82f6; background: #eff6ff; border-radius: 4px; }
        .grid-compact span { padding: 1px 0; }
    </style>
</head>
<body class="antialiased text-slate-800" x-data="printComponent()">
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('printComponent', () => ({
    savedContentData: @json($payment->print_data['savedContentData'] ?? new stdClass()),
    isSaving: false,
    async saveData() {
        this.isSaving = true;
        try {
            let response = await fetch('{{ route('payments.save-print', $payment->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    print_data: {
                        savedContentData: this.savedContentData,
                        checklist: this.checklist,
                        checklistGaji: this.checklistGaji,
                        checklistUP: this.checklistUP,
                        checklistGU: this.checklistGU,
                        checklistSPM: this.checklistSPM,
                        checklistSPM2: this.checklistSPM2,
                        checklistSPM3: this.checklistSPM3,
                        checklistSPM4: this.checklistSPM4,
                    }
                })
            });
            let result = await response.json();
            if(result.success) {
                alert('Data cetak berhasil disimpan permanen!');
            }
        } catch(e) {
            alert('Gagal menyimpan data.');
        } finally {
            this.isSaving = false;
        }
    }, 
    checklist: @json($payment->print_data['checklist'] ?? null) || [
        { no: 1, label: 'Dokumen Pelaksanaan Anggaran (DPA)', status: true, note: '' },
        { no: 2, label: 'Surat Penyediaan Dana (SPD)', status: true, note: '' },
        { no: 3, label: 'Kwitansi bermaterai yang ditandatangani oleh pihak ketiga, PPTK, Bendahara dan disetujui/ditandatangani oleh PA/KPA', status: true, note: '' },
        { no: 4, label: 'Surat Pernyataan Tanggung Jawab Pengajuan LS dari PPTK', status: true, note: '' },
        { no: 5, label: 'Faktur Pajak', status: true, note: '' },
        { no: 6, label: 'Surat perjanjian kerjasama/kontrak antara PA/KPA atau PPK (Pejabat Pembuat Komitmen) dengan pihak ketiga', status: true, note: '' },
        { no: 7, label: 'Bukti setor pajak (SSP) yang telah divalidasi oleh KPP apabila pajak telah disetor', status: false, note: '' },
        { no: 8, label: 'Berita Acara Pemeriksaan Hasil Pekerjaan dan Lampirannya', status: true, note: '' },
        { no: 9, label: 'Berita Acara Pembayaran', status: true, note: '' },
        { no: 10, label: 'Fotocopy rekening Bank dan NPWP pihak ketiga', status: true, note: '' },
        { no: 11, label: 'Surat Jalan (Surat pengiriman barang)', status: true, note: '' },
        { no: 12, label: 'Faktur / Nota Barang', status: true, note: '' },
        { no: 13, label: 'Surat Jaminan Bank atau yang Dipersamakan yang Dikeluarkan oleh Bank atau Lembaga Keuangan non Bank', status: true, note: '' },
        { no: 14, label: 'Surat Angkutan atau Konsosemen apabila Pengadaan Barang Dilaksanakan Di Luar Wilayah Kerja', status: false, note: '' },
        { no: 15, label: 'Surat pemberitahuan potongan denda keterlambatan pekerjaan dari PPTK yang diketahui dan ditandangani oleh PA/KPA apabila ada keterlambatan', status: false, note: '' },
        { no: 16, label: 'Surat keterangan keringanan / bebas pajak (Jika ada)', status: false, note: '' },
        { no: 17, label: 'Berita Acara Pemotongan Denda UMK (Jika ada)', status: false, note: '' },
        { no: 18, label: 'STS denda dan UMK (jika ada)', status: false, note: '' },
        { no: 19, label: 'NPWP Pihak Ketiga', status: true, note: '' }
    ],
    checklistGaji: @json($payment->print_data['checklistGaji'] ?? null) || [
        { no: 1, label: 'SPM LS GAJI /TKD', status: true, note: '' },
        { no: 2, label: 'KWITANSI', status: true, note: '' },
        { no: 3, label: 'FORM 33 ( Surat Permintaan Pembayaran LS Gaji dan Tunjangan)', status: true, note: '' },
        { no: 4, label: 'FORM 33 (Surat Pernyataan Tanggung Jawab LS)', status: true, note: '' },
        { no: 5, label: 'Listing Gaji /Rekap Daftar Gaji', status: true, note: '' },
        { no: 6, label: 'Rekap Potongan', status: true, note: '' },
        { no: 7, label: 'SPD', status: true, note: '' },
        { no: 8, label: 'DPA', status: true, note: '' }
    ],
    checklistUP: @json($payment->print_data['checklistUP'] ?? null) || [
        { no: 1, label: 'Dokumen Pelaksanaan Anggaran (DPA)', status: true, note: '' },
        { no: 2, label: 'Surat Penyediaan Dana (SPD)', status: true, note: '' },
        { no: 3, label: 'Kwitansi bermaterai yang ditandatangani oleh pihak ketiga, PPTK, Bendahara dan disetujui/ditandatangani oleh PA/KPA', status: true, note: '' },
        { no: 4, label: 'Surat Pernyataan Tanggung Jawab Pengajuan SPP UP', status: true, note: '' },
        { no: 5, label: 'Surat Pengesahan Pertanggung jawaban Belanja UP', status: true, note: '' },
        { no: 6, label: 'Tanda Terima Penyampaian Laporan Pertanggung Jawaban UP dari Fungsional PPKD', status: true, note: '' },
        { no: 7, label: 'Rekapitulasi Atas penyetoran PPN, PPH yang ditanda tangani oleh Bendahara Pengeluaran/Bendahara Pengeluaran Pembantu yang di ketahui PA/KPA', status: true, note: '' }
    ],
    checklistGU: @json($payment->print_data['checklistGU'] ?? null) || [
        { no: 1, label: 'Surat Permintaan Pembayaran Ganti Uang Persediaan (SPP-GU)', status: true, note: '' },
        { no: 2, label: 'Checklist Persyaratan Penerbitan SPP-GU yang ditandatangani PPK SKPD/UKPD', status: true, note: '' }
    ],
    nilaiKontrak: {{ $payment->contract?->nilai_kontrak ?? 0 }},
    terbilangTeks: '{{ $payment->contract?->terbilang_kontrak ?? '' }}',
    init() {
        this.updateTerbilang();
        // Populate static editables
        setTimeout(() => {
            document.querySelectorAll('[data-eid]').forEach(el => {
                let eid = el.getAttribute('data-eid');
                if (this.savedContentData[eid] !== undefined) {
                    el.innerText = this.savedContentData[eid];
                }
                el.addEventListener('input', () => {
                    this.savedContentData[eid] = el.innerText;
                });
            });
        }, 100);
    },
    updateTerbilang() {
        let hasil = this.generateTerbilang(this.nilaiKontrak).trim();
        if (hasil) this.terbilangTeks = hasil + ' RUPIAH';
        else this.terbilangTeks = 'NOL RUPIAH';
    },
    addRowSPP() { this.checklist.push({ no: this.checklist.length + 1, label: '...', status: true, note: '' }); },
    removeLastRowSPP() { if(this.checklist.length > 0) this.checklist.pop(); },
    generateTerbilang(angka) {
        angka = Math.floor(angka); if (angka < 0) return '';
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
    checklistSPM: @json($payment->print_data['checklistSPM'] ?? null) || [
        { no: 1, jenis: '{{ $payment->keperluan }}', syarat: 'Surat Permintaan Pembayaran LS', ada: true },
        { no: 2, jenis: '', syarat: 'Checklist Persyaratan Penerbitan SPP-LS Pengadaan Barang dan Jasa yang ditandatangani oleh PPTK dan PPK', ada: true }
    ],
    addRowSPM() { this.checklistSPM.push({ no: this.checklistSPM.length + 1, jenis: '', syarat: '...', ada: true }); },
    removeLastRowSPM() { if(this.checklistSPM.length > 0) this.checklistSPM.pop(); },
    checklistSPM2: @json($payment->print_data['checklistSPM2'] ?? null) || [
        { no: 1, jenis: 'SPM – LS PENGADAAN JASA KONSTRUKSI', syarat: 'Surat Permintaan Pembayaran LS Jasa Konstruksi', ada: true },
        { no: 2, jenis: '', syarat: 'Checklist Persyaratan Penerbitan SPP-LS Pengadaan Jasa Konstruksi yang ditandatangani oleh PPTK dan PPK', ada: true }
    ],
    addRowSPM2() { this.checklistSPM2.push({ no: this.checklistSPM2.length + 1, jenis: '', syarat: '...', ada: true }); },
    removeLastRowSPM2() { if(this.checklistSPM2.length > 0) this.checklistSPM2.pop(); },
    checklistSPM3: @json($payment->print_data['checklistSPM3'] ?? null) || [
        { no: 1, jenis: 'SPM – LS JASA KONSULTAN', syarat: 'Surat Permintaan Pembayaran LS Jasa Konsultan', ada: true },
        { no: 2, jenis: '', syarat: 'Checklist Persyaratan Penerbitan SPP-LS Jasa Konsultan yang ditandatangani oleh PPTK dan PPK', ada: true }
    ],
    addRowSPM3() { this.checklistSPM3.push({ no: this.checklistSPM3.length + 1, jenis: '', syarat: '...', ada: true }); },
    removeLastRowSPM3() { if(this.checklistSPM3.length > 0) this.checklistSPM3.pop(); },
    checklistSPM4: @json($payment->print_data['checklistSPM4'] ?? null) || [
        { no: 1, jenis: 'SPM – LS GAJI / TUNJANGAN', syarat: 'Surat Permintaan Pembayaran LS Gaji / Tunjangan', ada: true },
        { no: 2, jenis: '', syarat: 'Checklist Persyaratan Penerbitan SPP-LS Gaji/Tunjangan yang ditandatangani oleh PPTK dan PPK', ada: true }
    ],
    addRowSPM4() { this.checklistSPM4.push({ no: this.checklistSPM4.length + 1, jenis: '', syarat: '...', ada: true }); },
    removeLastRowSPM4() { if(this.checklistSPM4.length > 0) this.checklistSPM4.pop(); }
}));
        });
    </script>>

    <!-- UI Overlay (No Print) -->
    <div class="no-print sticky top-0 z-50 bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between shadow-sm">
        <div class="flex gap-4 items-center">
            <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center"><i data-lucide="printer" class="w-6 h-6"></i></div>
            <div><h1 class="text-lg font-black text-slate-800 tracking-tight">Dokumen SPP & SPM (9 Halaman)</h1><p class="text-xs text-slate-500 font-bold uppercase text-blue-600">Lengkap dengan Verifikasi PPK (GU & LS)</p></div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex flex-col gap-1 mr-4 border-r border-slate-200 pr-4">
                <div class="flex gap-2">
                    <span class="text-[9px] font-black uppercase text-slate-400 w-12 pt-2">Hal 1:</span>
                    <button @click="addRowSPP()" class="px-2 py-1 bg-blue-50 text-blue-600 font-bold rounded-md border border-blue-100 hover:bg-blue-100 transition-all text-[10px] flex items-center gap-1">Tambah</button>
                    <button @click="removeLastRowSPP()" class="px-2 py-1 bg-slate-50 text-slate-500 font-bold rounded-md border border-slate-100 hover:bg-slate-100 transition-all text-[10px] flex items-center gap-1">Hapus</button>
                </div>
                <div class="flex gap-2">
                    <span class="text-[9px] font-black uppercase text-slate-400 w-12 pt-2">Hal 2:</span>
                    <button @click="addRowSPM()" class="px-2 py-1 bg-emerald-50 text-emerald-600 font-bold rounded-md border border-emerald-100 hover:bg-emerald-100 transition-all text-[10px] flex items-center gap-1">Tambah</button>
                    <button @click="removeLastRowSPM()" class="px-2 py-1 bg-slate-50 text-slate-500 font-bold rounded-md border border-slate-100 hover:bg-slate-100 transition-all text-[10px] flex items-center gap-1">Hapus</button>
                </div>
                <div class="flex gap-2">
                    <span class="text-[9px] font-black uppercase text-slate-400 w-12 pt-2">Hal 3:</span>
                    <button @click="addRowSPM2()" class="px-2 py-1 bg-orange-50 text-orange-600 font-bold rounded-md border border-orange-100 hover:bg-orange-100 transition-all text-[10px] flex items-center gap-1">Tambah</button>
                    <button @click="removeLastRowSPM2()" class="px-2 py-1 bg-slate-50 text-slate-500 font-bold rounded-md border border-slate-100 hover:bg-slate-100 transition-all text-[10px] flex items-center gap-1">Hapus</button>
                </div>
                <div class="flex gap-2">
                    <span class="text-[9px] font-black uppercase text-slate-400 w-12 pt-2">Hal 4:</span>
                    <button @click="addRowSPM3()" class="px-2 py-1 bg-purple-50 text-purple-600 font-bold rounded-md border border-purple-100 hover:bg-purple-100 transition-all text-[10px] flex items-center gap-1">Tambah</button>
                    <button @click="removeLastRowSPM3()" class="px-2 py-1 bg-slate-50 text-slate-500 font-bold rounded-md border border-slate-100 hover:bg-slate-100 transition-all text-[10px] flex items-center gap-1">Hapus</button>
                </div>
                <div class="flex gap-2">
                    <span class="text-[9px] font-black uppercase text-slate-400 w-12 pt-2">Hal 5:</span>
                    <button @click="addRowSPM4()" class="px-2 py-1 bg-rose-50 text-rose-600 font-bold rounded-md border border-rose-100 hover:bg-rose-100 transition-all text-[10px] flex items-center gap-1">Tambah</button>
                    <button @click="removeLastRowSPM4()" class="px-2 py-1 bg-slate-50 text-slate-500 font-bold rounded-md border border-slate-100 hover:bg-slate-100 transition-all text-[10px] flex items-center gap-1">Hapus</button>
                </div>
            </div>
            <button @click="saveData()" :disabled="isSaving" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all flex items-center gap-2 mr-2">
                <i data-lucide="save" class="w-5 h-5"></i> <span x-text="isSaving ? 'Menyimpan...' : 'Simpan'"></span>
            </button>
            <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all flex items-center gap-2"><i data-lucide="printer" class="w-5 h-5"></i> Cetak</button>
            <a href="{{ route('payments.index') }}" class="px-4 py-2.5 bg-white text-slate-500 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 text-sm">Kembali</a>
        </div>
    </div>

    <div class="print-container flex flex-col items-center">
        
        <!-- PAGE 1: CHECKLIST SPP -->
        <div class="print-area font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-6 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1">
                    <h1 class="text-[12pt] font-bold leading-tight uppercase">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[9pt] leading-tight mt-1 font-sans">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            <div class="text-center mb-6 uppercase underline font-black text-[12pt]">CHECK LIST PENERBITAN SURAT PERMINTAAN PEMBAYARAN (SPP)</div>
            <div class="grid grid-cols-[160px_10px_1fr] gap-y-1 mb-4 text-[10pt] leading-tight">
                <span class="font-bold">Nomor SPM</span><span>:</span><span contenteditable="true" data-eid="2" class="font-bold">{{ $payment->no_spm }}</span>
                <span>Tanggal SPM</span><span>:</span><span contenteditable="true" data-eid="3">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span>
                <span>SKPD</span><span>:</span><span contenteditable="true" data-eid="4" class="font-bold uppercase">Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara</span>
                <span>Jenis Tagihan</span><span>:</span><span contenteditable="true" data-eid="5" class="font-bold">LS Pengadaan Barang dan Jasa</span>
            </div>
            <p class="mb-4 text-[9.5pt] leading-relaxed">Bahwa berdasarkan hasil verifikasi terhadap pengajuan SPP telah dilakukan verifikasi terhadap kelengkapan pembayaran sesuai peraturan perundang-undangan yang terdiri dari :</p>
            <table class="w-full border-collapse border-[1.5px] border-black text-[8.5pt]">
                <thead><tr class="bg-slate-50"><th class="border border-black px-2 py-2 w-10 uppercase text-center">NO</th><th class="border border-black px-2 py-2 text-left uppercase">BERKAS PERSYARATAN</th><th class="border border-black px-2 py-2 w-20 text-center uppercase">SESUAI</th><th class="border border-black px-2 py-2 w-28 text-left uppercase">KETERANGAN</th></tr></thead>
                <tbody>
                    <template x-for="(item, index) in checklist" :key="index">
                        <tr>
                            <td class="border border-black px-2 py-1 text-center font-bold" x-text="item.no"></td>
                            <td class="border border-black px-2 py-1 leading-tight" contenteditable="true" x-text="item.label" @input="item.label = $el.innerText"></td>
                            <td class="border border-black px-2 py-1 text-center cursor-pointer" @click="item.status = !item.status; if(!item.status) item.note = ''; else if(!item.note) item.note = '100%';"><span class="text-[12pt] font-black" x-text="item.status ? '√' : '-'"></span></td>
                            <td class="border border-black px-2 py-1" contenteditable="true" x-text="item.note" @input="item.note = $el.innerText"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="mt-4 text-justify text-[9.5pt] leading-relaxed">Atas penatausahaan dan pengarsipan dokumen tersebut sepenuhnya menjadi tanggung-jawab kami dan dokumen sesuai sebagai persyaratan untuk pengajuan perintah membayar.</p>
            <div class="mt-8 grid grid-cols-2 text-center gap-10">
                <div class="flex flex-col items-center">
                    <p class="text-[9pt]">Jakarta, <span contenteditable="true" data-eid="6">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p>
                    <p class="font-bold uppercase mt-2 text-[9pt] leading-tight">PEJABAT PELAKSANA TEKNIS KEGIATAN<br>(PPTK)</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-[10pt]" contenteditable="true" data-eid="7">APRIYANI TALAOHU</p><p class="text-[9pt]">NIP. <span contenteditable="true" data-eid="8">197604052008042001</span></p></div>
                </div>
                <div class="flex flex-col items-center pt-[22px]">
                    <p class="font-bold uppercase text-[9pt] leading-tight text-center">KEPALA SUB. BAGIAN TATA USAHA<br>SUDIN SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-[10pt]" contenteditable="true" data-eid="9">Deny Tri Hendarto</p><p class="text-[9pt]">NIP. <span contenteditable="true" data-eid="10">198111092010011017</span></p></div>
                </div>
            </div>
        </div>

        <!-- PAGE 1a: CHECKLIST SPP -->
          <div class="print-area font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-6 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1">
                    <h1 class="text-[12pt] font-bold leading-tight uppercase">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[9pt] leading-tight mt-1 font-sans">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            <div class="text-center mb-6 uppercase underline font-black text-[12pt]">
                CHECK LIST PENERBITAN SURAT PERMINTAAN PEMBAYARAN (SPP)<br>
                GAJI DAN TUNJANGAN (LS)
            </div>
            <p class="mb-4 text-[9.5pt] leading-relaxed">Bahwa berdasarkan hasil verifikasi terhadap pengajuan SPP telah dilakukan verifikasi terhadap kelengkapan pembayaran sesuai peraturan perundang-undangan yang terdiri dari :</p>
            <table class="w-full border-collapse border-[1.5px] border-black text-[8.5pt]">
                <thead><tr class="bg-slate-50"><th class="border border-black px-2 py-2 w-10 uppercase text-center">NO</th><th class="border border-black px-2 py-2 text-left uppercase">BERKAS PERSYARATAN</th><th class="border border-black px-2 py-2 w-20 text-center uppercase">SESUAI</th><th class="border border-black px-2 py-2 w-28 text-left uppercase">KETERANGAN</th></tr></thead>
                <tbody>
                    <template x-for="(item, index) in checklistGaji" :key="index">
                        <tr>
                            <td class="border border-black px-2 py-1 text-center font-bold" x-text="item.no"></td>
                            <td class="border border-black px-2 py-1 leading-tight" contenteditable="true" x-text="item.label" @input="item.label = $el.innerText"></td>
                            <td class="border border-black px-2 py-1 text-center cursor-pointer" @click="item.status = !item.status; if(!item.status) item.note = ''; else if(!item.note) item.note = '100%';"><span class="text-[12pt] font-black" x-text="item.status ? '√' : '-'"></span></td>
                            <td class="border border-black px-2 py-1" contenteditable="true" x-text="item.note" @input="item.note = $el.innerText"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="mt-4 text-justify text-[9.5pt] leading-relaxed">Atas penatausahaan dan pengarsipan dokumen tersebut sepenuhnya menjadi tanggung-jawab kami dan dokumen sesuai sebagai persyaratan untuk pengajuan perintah membayar.</p>
            <div class="mt-8 grid grid-cols-2 text-center gap-10">
                <div class="flex flex-col items-center">
                    <p class="text-[9pt]">Jakarta, <span contenteditable="true" data-eid="11">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p>
                    <p class="font-bold uppercase mt-2 text-[9pt] leading-tight">PEJABAT PELAKSANA TEKNIS KEGIATAN<br>(PPTK)</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-[10pt]" contenteditable="true" data-eid="12">APRIYANI TALAOHU</p><p class="text-[9pt]">NIP. <span contenteditable="true" data-eid="13">197604052008042001</span></p></div>
                </div>
                <div class="flex flex-col items-center pt-[22px]">
                    <p class="font-bold uppercase text-[9pt] leading-tight text-center">KEPALA SUB. BAGIAN TATA USAHA<br>SUDIN SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-[10pt]" contenteditable="true" data-eid="14">Deny Tri Hendarto</p><p class="text-[9pt]">NIP. <span contenteditable="true" data-eid="15">198111092010011017</span></p></div>
                </div>
            </div>
        </div>

        <!-- PAGE 1b: CHECKLIST SPP UP -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-6 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1">
                    <h1 class="text-[12pt] font-bold leading-tight uppercase">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[9pt] leading-tight mt-1 font-sans">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            <div class="text-center mb-6 uppercase underline font-black text-[12pt]">
                CHECK LIST PENERBITAN SURAT PERMINTAAN PEMBAYARAN (SPP)<br>
                UANG PERSEDIAAN (UP)
            </div>
            <p class="mb-4 text-[9.5pt] leading-relaxed">Bahwa berdasarkan hasil verifikasi terhadap pengajuan SPP telah dilakukan verifikasi terhadap kelengkapan pembayaran sesuai peraturan perundang-undangan yang terdiri dari :</p>
            <table class="w-full border-collapse border-[1.5px] border-black text-[8.5pt]">
                <thead><tr class="bg-slate-50"><th class="border border-black px-2 py-2 w-10 uppercase text-center">NO</th><th class="border border-black px-2 py-2 text-left uppercase">BERKAS PERSYARATAN</th><th class="border border-black px-2 py-2 w-20 text-center uppercase">SESUAI</th><th class="border border-black px-2 py-2 w-28 text-left uppercase">KETERANGAN</th></tr></thead>
                <tbody>
                    <template x-for="(item, index) in checklistUP" :key="index">
                        <tr>
                            <td class="border border-black px-2 py-1 text-center font-bold" x-text="item.no"></td>
                            <td class="border border-black px-2 py-1 leading-tight" contenteditable="true" x-text="item.label" @input="item.label = $el.innerText"></td>
                            <td class="border border-black px-2 py-1 text-center cursor-pointer" @click="item.status = !item.status; if(!item.status) item.note = ''; else if(!item.note) item.note = '100%';"><span class="text-[12pt] font-black" x-text="item.status ? '√' : '-'"></span></td>
                            <td class="border border-black px-2 py-1" contenteditable="true" x-text="item.note" @input="item.note = $el.innerText"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
             <div class="mt-8 grid grid-cols-2 text-center gap-10">
                <div class="flex flex-col items-center">
                    <!-- Kolom kiri kosong -->
                </div>
                <div class="flex flex-col items-center">
                    <p class="text-[9pt]">Jakarta, <span contenteditable="true" data-eid="16">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p>
                    <p class="font-bold uppercase mt-2 text-[9pt] leading-tight text-center">KEPALA SUB. BAGIAN TATA USAHA<br>SUDIN SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA (PPK)</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-[10pt]" contenteditable="true" data-eid="17">Deny Tri Hendarto</p><p class="text-[9pt]">NIP. <span contenteditable="true" data-eid="18">198111092010011017</span></p></div>
                </div>
            </div>
        </div>

        <!-- PAGE 1c: CHECKLIST SPP GU -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-6 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1">
                    <h1 class="text-[12pt] font-bold leading-tight uppercase">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[9pt] leading-tight mt-1 font-sans">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            <div class="text-center mb-6 uppercase underline font-black text-[12pt]">
                CHECK LIST PENERBITAN SURAT PERMINTAAN PEMBAYARAN (SPP)<br>
                GANTI UANG PERSEDIAAN (GU)
            </div>
              <table class="w-full border-collapse border-[1.5px] border-black text-[8.5pt]">
                <thead><tr class="bg-slate-50"><th class="border border-black px-2 py-2 w-10 uppercase text-center">NO</th><th class="border border-black px-2 py-2 text-left uppercase">BERKAS PERSYARATAN</th><th class="border border-black px-2 py-2 w-20 text-center uppercase">SESUAI</th><th class="border border-black px-2 py-2 w-28 text-left uppercase">KETERANGAN</th></tr></thead>
                <tbody>
                    <template x-for="(item, index) in checklistGU" :key="index">
                        <tr>
                            <td class="border border-black px-2 py-1 text-center font-bold" x-text="item.no"></td>
                            <td class="border border-black px-2 py-1 leading-tight" contenteditable="true" x-text="item.label" @input="item.label = $el.innerText"></td>
                            <td class="border border-black px-2 py-1 text-center cursor-pointer" @click="item.status = !item.status; if(!item.status) item.note = ''; else if(!item.note) item.note = '100%';"><span class="text-[12pt] font-black" x-text="item.status ? '√' : '-'"></span></td>
                            <td class="border border-black px-2 py-1" contenteditable="true" x-text="item.note" @input="item.note = $el.innerText"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
             <div class="mt-8 grid grid-cols-2 text-center gap-10">
                <div class="flex flex-col items-center">
                    <!-- Kolom kiri kosong -->
                </div>
                <div class="flex flex-col items-center">
                    <p class="text-[9pt]">Jakarta, <span contenteditable="true" data-eid="19">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p>
                    <p class="font-bold uppercase mt-2 text-[9pt] leading-tight text-center">KEPALA SUKU DINAS SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA (PA/KPA)</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-[10pt]" contenteditable="true" data-eid="20">Heria Suwandi</p><p class="text-[9pt]">NIP. <span contenteditable="true" data-eid="21">197101272006041009</span></p></div>
                </div>
            </div>
        </div>

        <!-- PAGE 2: CHECKLIST SPM -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-6 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center">
                    <h1 class="text-[11pt] font-bold leading-tight uppercase">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[13pt] font-bold leading-tight uppercase">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[11pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[8pt] leading-tight mt-1 font-sans">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            <div class="text-center mb-6"><h1 class="text-[12pt] font-black uppercase underline leading-tight">CHECKLIST PERSYARATAN PENERBITAN SURAT PERINTAH MEMBAYAR (SPM)</h1></div>
            <div class="grid grid-cols-[150px_10px_1fr] gap-y-1 mb-4 ml-4 text-[10.5pt]">
                <span class="font-bold">Nomor SPM</span><span>:</span><span contenteditable="true" data-eid="22" class="font-bold">{{ $payment->no_spm }}</span>
                <span class="font-bold">Tanggal SPM</span><span>:</span><span contenteditable="true" data-eid="23">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <p class="mb-4 text-[10pt] leading-relaxed">Bahwa berdasarkan hasil verifikasi terhadap pengajuan SPM telah dilakukan verifikasi terhadap kelengkapan dokumen untuk perintah membayar sesuai peraturan perundang-undangan yang terdiri dari :</p>
            <table class="w-full border-collapse border-[1.5px] border-black text-[10pt] mb-4">
                <thead><tr class="bg-slate-50"><th class="border border-black px-2 py-3 w-10 text-center uppercase text-[9pt]">NO</th><th class="border border-black px-3 py-3 text-left w-[220px] uppercase text-[9pt]">JENIS TAGIHAN</th><th class="border border-black px-3 py-3 text-left uppercase text-[9pt]">PERSYARATAN PENERBITAN</th><th class="border border-black px-2 py-3 w-16 text-center uppercase text-[9pt]">ADA</th><th class="border border-black px-2 py-3 w-16 text-center uppercase text-[9pt]">TIDAK</th></tr></thead>
                <tbody>
                    <template x-for="(item, index) in checklistSPM" :key="index">
                        <tr>
                            <td class="border border-black px-2 py-3 text-center font-bold" x-text="item.no"></td>
                            <td class="border border-black px-3 py-3 font-bold uppercase leading-tight text-[9pt]" contenteditable="true" x-text="item.jenis" @input="item.jenis = $el.innerText"></td>
                            <td class="border border-black px-3 py-3 leading-tight text-[9.5pt]" contenteditable="true" x-text="item.syarat" @input="item.syarat = $el.innerText"></td>
                            <td class="border border-black px-2 py-3 text-center cursor-pointer" @click="item.ada = true"><span class="text-[14pt] font-black text-blue-600" x-show="item.ada">√</span></td>
                            <td class="border border-black px-2 py-3 text-center cursor-pointer" @click="item.ada = false"><span class="text-[14pt] font-black text-rose-600" x-show="!item.ada">√</span></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="text-justify text-[10pt] leading-relaxed mb-8">Atas penatausahaan dan pengarsipan dokumen tersebut sepenuhnya menjadi tanggung jawab saya dan dokumen tersebut dinyatakan sesuai sebagai persyaratan untuk pengajuan perintah pencairan dana.</p>
            <div class="mt-8 flex flex-col items-end mr-4">
                <div class="text-center min-w-[350px]">
                    <p class="text-[10pt]">Jakarta, <span contenteditable="true" data-eid="24">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p>
                    <p class="font-bold uppercase mt-2 text-center text-[10pt]">KEPALA SUKU DINAS SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA<br>(PA/KPA)</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-center" contenteditable="true" data-eid="25">HERIA SUWANDI</p><p class="text-center text-[10pt]">NIP. <span contenteditable="true" data-eid="26">197101272006041009</span></p></div>
                </div>
            </div>
        </div>

        <!-- PAGE 3: CHECKLIST SPM - LS PENGADAAN JASA KONSTRUKSI -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-6 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center">
                    <h1 class="text-[11pt] font-bold leading-tight uppercase">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[13pt] font-bold leading-tight uppercase">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[11pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[8pt] leading-tight mt-1 font-sans">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            <div class="text-center mb-6"><h1 class="text-[12pt] font-black uppercase underline leading-tight">CHECKLIST PERSYARATAN PENERBITAN SURAT PERINTAH MEMBAYAR (SPM)</h1></div>
            <div class="grid grid-cols-[150px_10px_1fr] gap-y-1 mb-4 ml-4 text-[10.5pt]">
                <span class="font-bold">Nomor SPM</span><span>:</span><span contenteditable="true" data-eid="27" class="font-bold">{{ $payment->no_spm }}</span>
                <span class="font-bold">Tanggal SPM</span><span>:</span><span contenteditable="true" data-eid="28">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <p class="mb-4 text-[10pt] leading-relaxed">Bahwa berdasarkan hasil verifikasi terhadap pengajuan SPM telah dilakukan verifikasi terhadap kelengkapan dokumen untuk perintah membayar sesuai peraturan perundang-undangan yang terdiri dari :</p>
            <table class="w-full border-collapse border-[1.5px] border-black text-[10pt] mb-4">
                <thead><tr class="bg-slate-50"><th class="border border-black px-2 py-3 w-10 text-center uppercase text-[9pt]">NO</th><th class="border border-black px-3 py-3 text-left w-[220px] uppercase text-[9pt]">JENIS TAGIHAN</th><th class="border border-black px-3 py-3 text-left uppercase text-[9pt]">PERSYARATAN PENERBITAN</th><th class="border border-black px-2 py-3 w-16 text-center uppercase text-[9pt]">ADA</th><th class="border border-black px-2 py-3 w-16 text-center uppercase text-[9pt]">TIDAK</th></tr></thead>
                <tbody>
                    <template x-for="(item, index) in checklistSPM2" :key="index">
                        <tr>
                            <td class="border border-black px-2 py-3 text-center font-bold" x-text="item.no"></td>
                            <td class="border border-black px-3 py-3 font-bold uppercase leading-tight text-[9pt]" contenteditable="true" x-text="item.jenis" @input="item.jenis = $el.innerText"></td>
                            <td class="border border-black px-3 py-3 leading-tight text-[9.5pt]" contenteditable="true" x-text="item.syarat" @input="item.syarat = $el.innerText"></td>
                            <td class="border border-black px-2 py-3 text-center cursor-pointer" @click="item.ada = true"><span class="text-[14pt] font-black text-blue-600" x-show="item.ada">√</span></td>
                            <td class="border border-black px-2 py-3 text-center cursor-pointer" @click="item.ada = false"><span class="text-[14pt] font-black text-rose-600" x-show="!item.ada">√</span></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="text-justify text-[10pt] leading-relaxed mb-8">Atas penatausahaan dan pengarsipan dokumen tersebut sepenuhnya menjadi tanggung jawab saya dan dokumen tersebut dinyatakan sesuai sebagai persyaratan untuk pengajuan perintah pencairan dana.</p>
            <div class="mt-8 flex flex-col items-end mr-4">
                <div class="text-center min-w-[350px]">
                    <p class="text-[10pt]">Jakarta, <span contenteditable="true" data-eid="29">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p>
                    <p class="font-bold uppercase mt-2 text-center text-[10pt]">KEPALA SUKU DINAS SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA<br>(PA/KPA)</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-center" contenteditable="true" data-eid="30">HERIA SUWANDI</p><p class="text-center text-[10pt]">NIP. <span contenteditable="true" data-eid="31">197101272006041009</span></p></div>
                </div>
            </div>
        </div>

        <!-- PAGE 4: CHECKLIST SPM - LS JASA KONSULTAN -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-6 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center">
                    <h1 class="text-[11pt] font-bold leading-tight uppercase">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[13pt] font-bold leading-tight uppercase">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[11pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[8pt] leading-tight mt-1 font-sans">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            <div class="text-center mb-6"><h1 class="text-[12pt] font-black uppercase underline leading-tight">CHECKLIST PERSYARATAN PENERBITAN SURAT PERINTAH MEMBAYAR (SPM)</h1></div>
            <div class="grid grid-cols-[150px_10px_1fr] gap-y-1 mb-4 ml-4 text-[10.5pt]">
                <span class="font-bold">Nomor SPM</span><span>:</span><span contenteditable="true" data-eid="32" class="font-bold">{{ $payment->no_spm }}</span>
                <span class="font-bold">Tanggal SPM</span><span>:</span><span contenteditable="true" data-eid="33">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <p class="mb-4 text-[10pt] leading-relaxed">Bahwa berdasarkan hasil verifikasi terhadap pengajuan SPM telah dilakukan verifikasi terhadap kelengkapan dokumen untuk perintah membayar sesuai peraturan perundang-undangan yang terdiri dari :</p>
            <table class="w-full border-collapse border-[1.5px] border-black text-[10pt] mb-4">
                <thead><tr class="bg-slate-50"><th class="border border-black px-2 py-3 w-10 text-center uppercase text-[9pt]">NO</th><th class="border border-black px-3 py-3 text-left w-[220px] uppercase text-[9pt]">JENIS TAGIHAN</th><th class="border border-black px-3 py-3 text-left uppercase text-[9pt]">PERSYARATAN PENERBITAN</th><th class="border border-black px-2 py-3 w-16 text-center uppercase text-[9pt]">ADA</th><th class="border border-black px-2 py-3 w-16 text-center uppercase text-[9pt]">TIDAK</th></tr></thead>
                <tbody>
                    <template x-for="(item, index) in checklistSPM3" :key="index">
                        <tr>
                            <td class="border border-black px-2 py-3 text-center font-bold" x-text="item.no"></td>
                            <td class="border border-black px-3 py-3 font-bold uppercase leading-tight text-[9pt]" contenteditable="true" x-text="item.jenis" @input="item.jenis = $el.innerText"></td>
                            <td class="border border-black px-3 py-3 leading-tight text-[9.5pt]" contenteditable="true" x-text="item.syarat" @input="item.syarat = $el.innerText"></td>
                            <td class="border border-black px-2 py-3 text-center cursor-pointer" @click="item.ada = true"><span class="text-[14pt] font-black text-blue-600" x-show="item.ada">√</span></td>
                            <td class="border border-black px-2 py-3 text-center cursor-pointer" @click="item.ada = false"><span class="text-[14pt] font-black text-rose-600" x-show="!item.ada">√</span></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="text-justify text-[10pt] leading-relaxed mb-8">Atas penatausahaan dan pengarsipan dokumen tersebut sepenuhnya menjadi tanggung jawab saya dan dokumen tersebut dinyatakan sesuai sebagai persyaratan untuk pengajuan perintah pencairan dana.</p>
            <div class="mt-8 flex flex-col items-end mr-4">
                <div class="text-center min-w-[350px]">
                    <p class="text-[10pt]">Jakarta, <span contenteditable="true" data-eid="34">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p>
                    <p class="font-bold uppercase mt-2 text-center text-[10pt]">KEPALA SUKU DINAS SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA<br>(PA/KPA)</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-center" contenteditable="true" data-eid="35">HERIA SUWANDI</p><p class="text-center text-[10pt]">NIP. <span contenteditable="true" data-eid="36">197101272006041009</span></p></div>
                </div>
            </div>
        </div>

        <!-- PAGE 5: CHECKLIST SPM - LS GAJI / TUNJANGAN -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-6 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center">
                    <h1 class="text-[11pt] font-bold leading-tight uppercase">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[13pt] font-bold leading-tight uppercase">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[11pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[8pt] leading-tight mt-1 font-sans">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            <div class="text-center mb-6"><h1 class="text-[12pt] font-black uppercase underline leading-tight">CHECKLIST PERSYARATAN PENERBITAN SURAT PERINTAH MEMBAYAR (SPM)</h1></div>
            <div class="grid grid-cols-[150px_10px_1fr] gap-y-1 mb-4 ml-4 text-[10.5pt]">
                <span class="font-bold">Nomor SPM</span><span>:</span><span contenteditable="true" data-eid="37" class="font-bold">{{ $payment->no_spm }}</span>
                <span class="font-bold">Tanggal SPM</span><span>:</span><span contenteditable="true" data-eid="38">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <p class="mb-4 text-[10pt] leading-relaxed">Bahwa berdasarkan hasil verifikasi terhadap pengajuan SPM telah dilakukan verifikasi terhadap kelengkapan dokumen untuk perintah membayar sesuai peraturan perundang-undangan yang terdiri dari :</p>
            <table class="w-full border-collapse border-[1.5px] border-black text-[10pt] mb-4">
                <thead><tr class="bg-slate-50"><th class="border border-black px-2 py-3 w-10 text-center uppercase text-[9pt]">NO</th><th class="border border-black px-3 py-3 text-left w-[220px] uppercase text-[9pt]">JENIS TAGIHAN</th><th class="border border-black px-3 py-3 text-left uppercase text-[9pt]">PERSYARATAN PENERBITAN</th><th class="border border-black px-2 py-3 w-16 text-center uppercase text-[9pt]">ADA</th><th class="border border-black px-2 py-3 w-16 text-center uppercase text-[9pt]">TIDAK</th></tr></thead>
                <tbody>
                    <template x-for="(item, index) in checklistSPM4" :key="index">
                        <tr>
                            <td class="border border-black px-2 py-3 text-center font-bold" x-text="item.no"></td>
                            <td class="border border-black px-3 py-3 font-bold uppercase leading-tight text-[9pt]" contenteditable="true" x-text="item.jenis" @input="item.jenis = $el.innerText"></td>
                            <td class="border border-black px-3 py-3 leading-tight text-[9.5pt]" contenteditable="true" x-text="item.syarat" @input="item.syarat = $el.innerText"></td>
                            <td class="border border-black px-2 py-3 text-center cursor-pointer" @click="item.ada = true"><span class="text-[14pt] font-black text-blue-600" x-show="item.ada">√</span></td>
                            <td class="border border-black px-2 py-3 text-center cursor-pointer" @click="item.ada = false"><span class="text-[14pt] font-black text-rose-600" x-show="!item.ada">√</span></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="text-justify text-[10pt] leading-relaxed mb-8">Atas penatausahaan dan pengarsipan dokumen tersebut sepenuhnya menjadi tanggung jawab saya dan dokumen tersebut dinyatakan sesuai sebagai persyaratan untuk pengajuan perintah pencairan dana.</p>
            <div class="mt-8 flex flex-col items-end mr-4">
                <div class="text-center min-w-[350px]">
                    <p class="text-[10pt]">Jakarta, <span contenteditable="true" data-eid="39">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p>
                    <p class="font-bold uppercase mt-2 text-center text-[10pt]">KEPALA SUKU DINAS SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA<br>(PA/KPA)</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-center" contenteditable="true" data-eid="40">HERIA SUWANDI</p><p class="text-center text-[10pt]">NIP. <span contenteditable="true" data-eid="41">197101272006041009</span></p></div>
                </div>
            </div>
        </div>

        <!-- PAGE 6: RINGKASAN KONTRAK -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-6 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center">
                    <h1 class="text-[12pt] font-bold leading-tight uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[14pt] font-bold leading-tight uppercase text-center text-center">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[12pt] font-bold leading-tight uppercase text-center text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[9pt] leading-tight mt-1 font-sans">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            <div class="text-center mb-4 uppercase underline font-black text-[13pt]">RINGKASAN KONTRAK</div>
            <p class="mb-4 text-[10.5pt]">Kegiatan yang dananya dari DPA Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara :</p>
            <div class="grid gap-y-1 text-[10pt] leading-tight text-left" style="grid-template-columns: 30px 230px 10px 1fr;">
                <span>1.</span><span>Nomor & Tanggal DPA</span><span>:</span><span contenteditable="true" data-eid="42" class="font-bold">04/039/DPA/2026 Tgl.30 Des 2025</span>
                <span>2.</span><span>Tahun Anggaran</span><span>:</span><span contenteditable="true" data-eid="43">2026</span>
                <span>3.</span><span>Nomor & Tanggal SPD</span><span>:</span><span contenteditable="true" data-eid="44" class="font-bold">{{ $payment->no_spd }}  </span>
                <span>4.</span><span>Nama Kepala Unit</span><span>:</span><span contenteditable="true" data-eid="45" class="font-bold uppercase">HERIA SUWANDI</span>
                <span>5.</span><span>NIP Kepala Unit</span><span>:</span><span contenteditable="true" data-eid="46">197101272006041009</span>
                <span class="mt-1">6.</span><span class="mt-1 font-bold">Nomor & Tanggal SPK</span><span class="mt-1">:</span><span class="mt-1 font-bold" contenteditable="true" data-eid="47">{{ $payment->contract?->nomor_kontrak }} Tgl. {{ $payment->contract?->tgl_kontrak ? $payment->contract?->tgl_kontrak->translatedFormat('d F Y') : '-' }}</span>
                <span></span><span>Nomor Addendum I</span><span>:</span><span contenteditable="true" data-eid="48">{{ $payment->contract?->addendum_kontrak ?? '-' }}</span>
                <span></span><span>Nomor Addendum II</span><span>:</span><span contenteditable="true" data-eid="49">{{ $payment->contract?->addendum_kontrak2 ?? '-' }}</span>
                <span></span><span>Nomor Addendum III</span><span>:</span><span contenteditable="true" data-eid="50">-</span>
                <span>7.</span><span>Program</span><span>:</span><span contenteditable="true" data-eid="51" class="font-bold uppercase">{{ $payment->program }}</span>
                <span>8.</span><span>Kegiatan</span><span>:</span><span contenteditable="true" data-eid="52" class="font-bold uppercase leading-none">{{ $payment->kegiatanRef ? $payment->kegiatanRef->kode . ' ' . $payment->kegiatanRef->nama : $payment->kegiatan }}</span>
                <span>9.</span><span>Kode Rekening</span><span>:</span><span contenteditable="true" data-eid="53" class="font-bold">{{ $payment->perusahaan?->no_rekening }}</span>
                <span>10.</span><span>Wilayah/Lokasi</span><span>:</span><span contenteditable="true" data-eid="54">Jakarta Utara</span>
                <span>11.</span><span>Nama Perusahaan</span><span>:</span><span contenteditable="true" data-eid="55" class="font-bold uppercase">{{ $payment->perusahaan?->nama_perusahaan }}</span>
                <span>12.</span><span>Nama Direktur</span><span>:</span><span contenteditable="true" data-eid="56" class="font-bold uppercase">{{ $payment->perusahaan?->direktur }}</span>
                <span>13.</span><span>NPWP</span><span>:</span><span contenteditable="true" data-eid="57" class="font-bold">{{ $payment->perusahaan?->npwp }}</span>
                <span>14.</span><span>Alamat Kontraktor</span><span>:</span><span contenteditable="true" data-eid="58">{{ $payment->perusahaan?->alamat }}</span>
                <span>15.</span><span>Nomor/Tanggal Akte Perusahaan</span><span>:</span><span contenteditable="true" data-eid="59" class="font-bold">{{ $payment->perusahaan?->akte }} Tgl. {{ $payment->perusahaan?->tgl_akte ? \Carbon\Carbon::parse($payment->perusahaan?->tgl_akte)->translatedFormat('d F Y') : '-' }}</span>
                <span>16.</span><span>Nomor/Tanggal TDP</span><span>:</span><span contenteditable="true" data-eid="60" class="font-bold">{{ $payment->perusahaan?->tdp }} Tgl. {{ $payment->perusahaan?->tgl_tdp ? \Carbon\Carbon::parse($payment->perusahaan?->tgl_tdp)->translatedFormat('d F Y') : '-' }}</span>
                <span>17.</span><span><b>Nilai SPK/Kontrak</b></span><span>:</span><span contenteditable="true" data-eid="61" class="font-bold">Rp. {{ number_format($payment->contract?->nilai_kontrak ?? 0, 2, ',', '.') }}</span>
                <span>18.</span><span>Cara Pembayaran</span><span>:</span><span contenteditable="true" data-eid="62" class="font-bold">LS Barang / Jasa</span>
                <span>19.</span><span>Jangka Waktu Pelaksanaan</span><span>:</span><span contenteditable="true" data-eid="63">{{ $payment->contract?->jangka_waktu }}</span>
                <span>20.</span><span>Ketentuan Sanksi</span><span>:</span><span contenteditable="true" data-eid="64">1 % Dari Nilai Kontrak untuk setiap hari keterlambatan yang dilakukan</span>
                <span>21.</span><span>Jumlah Tagihan</span><span>:</span><span contenteditable="true" data-eid="65" class="font-bold">Rp. {{ number_format($payment->contract?->nilai_kontrak ?? 0, 2, ',', '.') }}</span>
                <span>22.</span><span>Tagihan</span><span>:</span><span contenteditable="true" data-eid="66" class="font-bold">100%</span>
                <span>23.</span><span>Rekening Bank</span><span>:</span><span contenteditable="true" data-eid="67" class="font-bold uppercase">{{ $payment->perusahaan?->bank }} / {{ $payment->perusahaan?->no_rekening }}</span>
                <span>24.</span><span>Nomor BAST</span><span>:</span><span contenteditable="true" data-eid="68" class="font-bold">{{ $payment->no_bast }}</span>
                <span>25.</span><span>Tgl BAST</span><span>:</span><span contenteditable="true" data-eid="69">{{ $payment->tgl_bast ? $payment->tgl_bast->translatedFormat('d F Y') : '-' }}</span>
                <span>26.</span><span>Kualifikasi Perusahaan</span><span>:</span><span contenteditable="true" data-eid="70"></span>
            </div>
            <div class="mt-8 flex flex-col items-center ml-[50%] text-center">
                <p class="font-bold uppercase text-[9pt]">Mengetahui</p>
                <p class="font-bold uppercase text-[9pt] leading-tight text-center">KEPALA SUKU DINAS SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA</p>
                <div class="mt-20"><p class="font-bold underline uppercase" contenteditable="true" data-eid="71">HERIA SUWANDI</p><p class="text-[9pt]">NIP. <span contenteditable="true" data-eid="72">197101272006041009</span></p></div>
            </div>
        </div>

        <!-- PAGE 7: KWITANSI -->
        <div class="print-area page-break font-serif">
            <div class="border-[1.5px] border-black p-8">
                <div class="flex items-center border-b-[2px] border-black pb-2 mb-6 text-center">
                    <div class="w-[80px] pr-3"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                    <div class="flex-1"><h1 class="text-[10pt] font-bold uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1><h2 class="text-[12pt] font-bold uppercase text-center text-center">DINAS SUMBER DAYA AIR</h2><h3 class="text-[10pt] font-bold uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3></div>
                </div>
                <div class="text-center mb-6"><h1 class="text-[16pt] font-black underline tracking-widest uppercase">KWITANSI</h1></div>
                <div class="space-y-4 px-4 text-[11pt]">
                    <div class="grid gap-x-2" style="grid-template-columns: 160px 10px 1fr;"><span>Nomor</span><span>:</span><span contenteditable="true" data-eid="73" class="font-bold">{{ $payment->no_kwi }}</span></div>
                    <div class="grid gap-x-2 min-h-[50px]" style="grid-template-columns: 160px 10px 1fr;"><span>Jumlah</span><span>:</span><span class="font-bold italic uppercase" x-text="'# ' + terbilangTeks + ' #'"></span></div>
                    <div class="grid gap-x-2" style="grid-template-columns: 160px 10px 1fr;"><span>Pembayaran</span><span>:</span><span contenteditable="true" data-eid="74" class="leading-relaxed">{{ $payment->keperluan }}</span></div>
                </div>
                <div class="mt-10 flex justify-between border-t-2 border-b-2 border-black py-4 px-6 bg-slate-50 font-black text-[14pt]">
                    <span>JUMLAH Rp.</span><span contenteditable="true" data-eid="75" x-text="new Intl.NumberFormat('id-ID').format(nilaiKontrak)" @blur="nilaiKontrak = parseFloat($el.innerText.replace(/\./g, '').replace(',', '.')) || 0; updateTerbilang();"></span>
                </div>
                <div class="mt-8 flex justify-between px-4 text-[9pt]"><div class="flex-1"></div><div class="text-left min-w-[250px]"><p>Jakarta, <span contenteditable="true" data-eid="76">{{ $payment->tgl_kwi ? $payment->tgl_kwi->translatedFormat('d F Y') : '-' }}</span></p></div></div>
                <div class="grid grid-cols-2 text-center gap-4 px-4 text-[9pt] leading-tight">
                    <div><p class="font-bold uppercase">Pejabat Pelaksana Teknis Kegiatan</p><p class="font-bold uppercase">Suku Dinas Sumber Daya Air</p><p class="font-bold uppercase text-center">Kota Administrasi Jakarta Utara</p><div class="mt-20"><p class="font-bold underline uppercase" contenteditable="true" data-eid="77">{{ $payment->pptk?->nama }}</p><p>NIP. <span contenteditable="true" data-eid="78">{{ $payment->pptk?->nip }}</span></p></div></div>
                    <div><p class="font-bold uppercase text-center">Bendahara Pengeluaran Pembantu</p><p class="font-bold uppercase">Suku Dinas Sumber Daya Air</p><p class="font-bold uppercase text-center">Kota Administrasi Jakarta Utara</p><div class="mt-20"><p class="font-bold underline uppercase" contenteditable="true" data-eid="79">R. Elly Prasojo</p><p>NIP. <span contenteditable="true" data-eid="80">197410252014121001</span></p></div></div>
                </div>
                <div class="mt-8 flex flex-col items-center text-center text-[9pt] leading-tight"><p class="font-bold uppercase">Mengetahui</p><p class="font-bold uppercase text-center">KEPALA SUKU DINAS SUMBER DAYA AIR</p><p class="font-bold uppercase text-center">KOTA ADMINISTRASI JAKARTA UTARA</p><div class="mt-20"><p class="font-bold underline uppercase" contenteditable="true" data-eid="81">HERIA SUWANDI</p><p>NIP. <span contenteditable="true" data-eid="82">197101272006041009</span></p></div></div>
            </div>
        </div>

        <!-- PAGE 8: KWITANSI (TANPA PPTK) -->
        <div class="print-area page-break font-serif">
            <div class="border-[1.5px] border-black p-8">
                <div class="flex items-center border-b-[2px] border-black pb-2 mb-6 text-center">
                    <div class="w-[80px] pr-3"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                    <div class="flex-1"><h1 class="text-[10pt] font-bold uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1><h2 class="text-[12pt] font-bold uppercase text-center text-center">DINAS SUMBER DAYA AIR</h2><h3 class="text-[10pt] font-bold uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3></div>
                </div>
                <div class="text-center mb-6"><h1 class="text-[16pt] font-black underline tracking-widest uppercase">KWITANSI</h1></div>
                <div class="space-y-4 px-4 text-[11pt]">
                    <div class="grid gap-x-2" style="grid-template-columns: 160px 10px 1fr;"><span>Nomor</span><span>:</span><span contenteditable="true" data-eid="83" class="font-bold">{{ $payment->no_kwi }}</span></div>
                    <div class="grid gap-x-2 min-h-[50px]" style="grid-template-columns: 160px 10px 1fr;"><span>Jumlah</span><span>:</span><span class="font-bold italic uppercase" x-text="'# ' + terbilangTeks + ' #'"></span></div>
                    <div class="grid gap-x-2" style="grid-template-columns: 160px 10px 1fr;"><span>Pembayaran</span><span>:</span><span contenteditable="true" data-eid="84" class="leading-relaxed">{{ $payment->keperluan }}</span></div>
                </div>
                <div class="mt-10 flex justify-between border-t-2 border-b-2 border-black py-4 px-6 bg-slate-50 font-black text-[14pt]">
                    <span>JUMLAH Rp.</span><span contenteditable="true" data-eid="85" x-text="new Intl.NumberFormat('id-ID').format(nilaiKontrak)" @blur="nilaiKontrak = parseFloat($el.innerText.replace(/\./g, '').replace(',', '.')) || 0; updateTerbilang();"></span>
                </div>
                <div class="mt-8 flex justify-between px-4 text-[9pt]"><div class="flex-1"></div><div class="text-left min-w-[250px]"><p>Jakarta, <span contenteditable="true" data-eid="86">{{ $payment->tgl_kwi ? $payment->tgl_kwi->translatedFormat('d F Y') : '-' }}</span></p></div></div>
                <div class="grid grid-cols-2 text-center gap-4 px-4 text-[9pt] leading-tight">
                    <div></div>
                    <div><p class="font-bold uppercase text-center">Bendahara Pengeluaran Pembantu</p><p class="font-bold uppercase">Suku Dinas Sumber Daya Air</p><p class="font-bold uppercase text-center">Kota Administrasi Jakarta Utara</p><div class="mt-20"><p class="font-bold underline uppercase" contenteditable="true" data-eid="87">R. Elly Prasojo</p><p>NIP. <span contenteditable="true" data-eid="88">197410252014121001</span></p></div></div>
                </div>
                <div class="mt-8 flex flex-col items-center text-center text-[9pt] leading-tight"><p class="font-bold uppercase">Mengetahui</p><p class="font-bold uppercase text-center">KEPALA SUKU DINAS SUMBER DAYA AIR</p><p class="font-bold uppercase text-center">KOTA ADMINISTRASI JAKARTA UTARA</p><div class="mt-20"><p class="font-bold underline uppercase" contenteditable="true" data-eid="89">HERIA SUWANDI</p><p>NIP. <span contenteditable="true" data-eid="90">197101272006041009</span></p></div></div>
            </div>
        </div>

      
        <!-- PAGE 9: SPTJM -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-8 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center"><h1 class="text-[12pt] font-bold leading-tight uppercase text-center text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1><h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2><h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3></div>
            </div>
            <div class="text-center mb-8"><h1 class="text-[12pt] font-black uppercase underline leading-tight text-center">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK GANTI UANG</h1><p class="font-bold mt-2">Nomor : <span contenteditable="true" data-eid="91">{{ $payment->no_spm }}</span></p></div>
            <p class="text-justify leading-relaxed text-[10pt] mb-4">Sehubungan dengan Surat Perintah Membayar (SPM-GU) nomor <span class="font-bold">{{ $payment->no_spm }}</span> tanggal {{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }} yang saya ajukan sebesar Rp. <span contenteditable="true" data-eid="92" x-text="new Intl.NumberFormat('id-ID').format(nilaiKontrak)"></span> (<span x-text="terbilangTeks.toLowerCase()"></span>) untuk keperluan SKPD/ UNIT SKPD Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara Tahun Anggaran 2026 dengan ini menyatakan dengan sebenarnya bahwa:</p>
            <ol class="list-decimal ml-8 space-y-2 text-[10pt] text-justify leading-relaxed mb-4">
                <li>Bukti Pertanggungjawaban atas pengunaan Ganti Uang (GU) telah lengkap, diverifikasi, dan mendapat pengesahaan.</li>
                <li>Saya bertanggungjawab secara penuh atas penggunaan (GU) tersebut diatas sesuai dengan ketentuan peraturan perudang-undangan.</li>
                <li>Jumlah (GU) tersebut diatas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanakan sesuai DPA/DPPA-SKPD/UNIT SKPD.</li>
                <li>Jumlah (GU) tersebut diatas tidak akan kami gunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku harus dilakukan dengan pembayaran langsung.</li>
                <li>Apabila dikemudian hari terdapat kelebihan pembayaran atas belanja tersebut, saya bersedia untuk menyetor kelebihannya ke kas umum daerah.</li>
            </ol>
            <p class="text-justify text-[10pt] leading-relaxed mb-8">Demikian Surat Pernyataan ini dibuat untuk melengkapi persyaratan SPM-GU SKPD/UNIT SKPD saya.</p>
            <div class="flex flex-col items-end mr-4"><div class="text-center min-w-[350px] text-[10pt]"><p>Jakarta, <span contenteditable="true" data-eid="93">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p><p class="mt-1 text-center">Kepala Suku Dinas Sumber Daya Air<br>Kota Administrasi Jakarta Utara</p><div class="mt-24"><p class="font-bold underline uppercase text-center" contenteditable="true" data-eid="94">HERIA SUWANDI</p><p class="text-center">NIP. <span contenteditable="true" data-eid="95">197101272006041009</span></p></div></div></div>
        </div>

        <!-- PAGE 10: SPTJM GANTI UANG (DUPLIKAT) -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-8 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center"><h1 class="text-[12pt] font-bold leading-tight uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1><h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2><h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3></div>
            </div>
            <div class="text-center mb-8"><h1 class="text-[12pt] font-black uppercase underline leading-tight text-center">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK UANG PERSEDIAAN</h1><p class="font-bold mt-2">Nomor : <span contenteditable="true" data-eid="96">{{ $payment->no_spm }}</span></p></div>
            <p class="text-justify leading-relaxed text-[10pt] mb-4">Sehubungan dengan Surat Perintah Membayar (SPM-UP) nomor <span class="font-bold">{{ $payment->no_spm }}</span> tanggal {{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }} yang saya ajukan sebesar Rp. <span contenteditable="true" data-eid="97" x-text="new Intl.NumberFormat('id-ID').format(nilaiKontrak)"></span> (<span x-text="terbilangTeks.toLowerCase()"></span>) untuk keperluan SKPD/ UNIT SKPD Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara Tahun Anggaran 2026 dengan ini menyatakan dengan sebenarnya bahwa:</p>
            <ol class="list-decimal ml-8 space-y-2 text-[10pt] text-justify leading-relaxed mb-4">
                <li>Bukti Pertanggungjawaban atas pengunaan Ganti Uang (UP) telah lengkap, diverifikasi, dan mendapat pengesahaan.</li>
                <li>Saya bertanggungjawab secara penuh atas penggunaan (UP) tersebut diatas sesuai dengan ketentuan peraturan perudang-undangan.</li>
                <li>Jumlah (UP) tersebut diatas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan kami laksanakan sesuai DPA/DPPA-SKPD/UNIT SKPD.</li>
                <li>Jumlah (UP) tersebut diatas tidak akan kami gunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku harus dilakukan dengan pembayaran langsung.</li>
                <li>Apabila dikemudian hari terdapat kelebihan pembayaran atas belanja tersebut, saya bersedia untuk menyetor kelebihannya ke kas umum daerah.</li>
            </ol>
            <p class="text-justify text-[10pt] leading-relaxed mb-8">Demikian Surat Pernyataan ini dibuat untuk melengkapi persyaratan SPM-UP SKPD/UNIT SKPD saya.</p>
            <div class="flex flex-col items-end mr-4"><div class="text-center min-w-[350px] text-[10pt]"><p>Jakarta, <span contenteditable="true" data-eid="98">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p><p class="mt-1 text-center">Kepala Suku Dinas Sumber Daya Air<br>Kota Administrasi Jakarta Utara</p><div class="mt-24"><p class="font-bold underline uppercase text-center" contenteditable="true" data-eid="99">HERIA SUWANDI</p><p class="text-center">NIP. <span contenteditable="true" data-eid="100">197101272006041009</span></p></div></div></div>
        </div>

          <!-- PAGE 11: VERIFIKASI PPTK -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-8 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center">
                    <h1 class="text-[12pt] font-bold leading-tight uppercase">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[14pt] font-bold leading-tight uppercase text-center text-center">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[12pt] font-bold leading-tight uppercase text-center text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                </div>
            </div>
            <div class="text-center mb-6"><h1 class="text-[11pt] font-black uppercase underline leading-tight text-center">SURAT PERNYATAAN VERIFIKASI PPTK</h1><h1 class="text-[11pt] font-black uppercase underline leading-tight text-center">ATAS KELENGKAPAN DAN KEABSAHAN DOKUMEN DAN LAMPIRAN SPP-LS</h1><p class="font-bold mt-2">Nomor : <span contenteditable="true" data-eid="101">{{ $payment->no_spp }}</span></p></div>
            <p class="mb-4 text-[10.5pt]">Saya yang bertanda tangan dibawah ini:</p>
            <div class="grid grid-cols-[100px_10px_1fr] gap-y-1 mb-6 ml-4 text-[10.5pt]">
                <span>Nama</span><span>:</span><span class="font-bold uppercase" contenteditable="true" data-eid="102">{{ $payment->pptk?->nama }}</span>
                <span>NIP</span><span>:</span><span contenteditable="true" data-eid="103">{{ $payment->pptk?->nip }}</span>
                <span>Jabatan</span><span>:</span><span contenteditable="true" data-eid="104" class="font-bold">Kepala Seksi Pemeliharaan Drainase</span>
                <span>Unit Kerja</span><span>:</span><span class="font-bold">Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara</span>
            </div>
            <p class="text-justify leading-relaxed text-[10.5pt] mb-4">Berdasarkan pengajuan Surat Permintaan Pembayaran LS Nomor <span class="font-bold">{{ $payment->no_spp }}</span> Tanggal {{ $payment->tgl_spp ? $payment->tgl_spp->translatedFormat('d F Y') : '-' }} telah dilakukan verifikasi terhadap kelengkapan dokumen pendukung sesuai dengan checklist terlampir. Atas surat permintaan pembayaran dan kelengkapan dokumen sebagaimana dimaksud dinyatakan lengkap dan sah sesuai peraturan perundang-undangan untuk dapat diproses sebagai persyaratan Perintah Membayar yang dituangkan dalam surat Surat Perintah Membayar. Jika dikemudian hari pernyataan saya ini tidak benar, maka saya bersedia diberikan sanksi sesuai peraturan yang berlaku.</p>
            <p class="text-justify text-[10.5pt] leading-relaxed mb-12">Demikian Surat ini saya buat dalam keadaan sadar dan tanpa paksaan dari pihak manapun.</p>
            <div class="flex flex-col items-end mr-4"><div class="text-center min-w-[350px] text-[10.5pt]"><p>Jakarta, <span contenteditable="true" data-eid="105">{{ $payment->tgl_spp ? $payment->tgl_spp->translatedFormat('d F Y') : '-' }}</span></p><p class="mt-1 font-bold text-center">Kepala Seksi Pemeliharaan Drainase<br>Suku Dinas Sumber Daya Air<br>Kota Administrasi Jakarta Utara</p><div class="mt-28"><p class="font-bold underline uppercase text-center" contenteditable="true" data-eid="106">{{ $payment->pptk?->nama }}</p><p class="text-center">NIP. <span contenteditable="true" data-eid="107">{{ $payment->pptk?->nip }}</span></p></div></div></div>
        </div>

        <!-- PAGE 12: SPTJM UP/LS -->
           <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-8 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center">
                    <h1 class="text-[12pt] font-bold leading-tight uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[14pt] font-bold leading-tight uppercase text-center text-center">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[12pt] font-bold leading-tight uppercase text-center text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[9pt] leading-tight mt-1 font-sans text-center">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            
            <div class="text-center mb-6 text-center"><h1 class="text-[11pt] font-black uppercase underline leading-tight text-center">SURAT PERNYATAAN VERIFIKASI PPK</h1><h1 class="text-[11pt] font-black uppercase underline leading-tight text-center">ATAS KELENGKAPAN DAN KEABSAHAN DOKUMEN DAN LAMPIRAN SPP LS</h1><p class="font-bold mt-2 text-center text-center">Nomor : <span contenteditable="true" data-eid="108">{{ $payment->no_spp }}</span></p></div>
            <p class="mb-4 text-[10.5pt]">Saya yang bertanda tangan dibawah ini:</p>
            <div class="grid grid-cols-[100px_10px_1fr] gap-y-1 mb-6 ml-4 text-[10.5pt]">
                <span>Nama</span><span>:</span><span class="font-bold uppercase" contenteditable="true" data-eid="109">Deny Tri Hendarto</span>
                <span>NIP</span><span>:</span><span contenteditable="true" data-eid="110">198111092010011017</span>
                <span>Jabatan</span><span>:</span><span contenteditable="true" data-eid="111" class="font-bold">Kepala Sub Bagian Tata Usaha</span>
                <span>Unit Kerja</span><span>:</span><span class="font-bold">Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara</span>
            </div>
            <p class="text-justify leading-relaxed text-[10.5pt] mb-4">Berdasarkan pengajuan Surat Permintaan Pembayaran LS Nomor <span class="font-bold">{{ $payment->no_spp }}</span> Tanggal {{ $payment->tgl_spp ? $payment->tgl_spp->translatedFormat('d F Y') : '-' }} telah dilakukan verifikasi terhadap kelengkapan dokumen pendukung sesuai dengan checklist terlampir. Atas surat permintaan pembayaran dan kelengkapan dokumen sebagaimana dimaksud dinyatakan lengkap dan sah sesuai peraturan perundang-undangan untuk dapat diproses sebagai persyaratan Perintah Membayar yang dituangkan dalam surat Surat Perintah Membayar. Jika dikemudian hari pernyataan saya ini tidak benar, maka saya bersedia diberikan sanksi sesuai peraturan yang berlaku.</p>
            <p class="text-justify text-[10.5pt] leading-relaxed mb-12">Demikian Surat ini saya buat dalam keadaan sadar dan tanpa paksaan dari pihak manapun.</p>
            <div class="flex flex-col items-end mr-4"><div class="text-center min-w-[350px] text-[10.5pt]"><p>Jakarta, <span contenteditable="true" data-eid="112">{{ $payment->tgl_spp ? $payment->tgl_spp->translatedFormat('d F Y') : '-' }}</span></p><p class="mt-1 font-bold text-center">Pejabat Penatausahaan Keuangan</p><div class="mt-32"><p class="font-bold underline uppercase text-center text-center" contenteditable="true" data-eid="113">Deny Tri Hendarto</p><p class="text-center text-center">NIP. <span contenteditable="true" data-eid="114">198111092010011017</span></p></div></div></div>
        </div>

           <!-- PAGE 13: SPTJM UP/LS -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-8 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center"><h1 class="text-[12pt] font-bold leading-tight uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1><h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2><h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3></div>
            </div>
            <div class="text-center mb-8"><h1 class="text-[12pt] font-black uppercase underline leading-tight text-center">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK LS</h1><p class="font-bold mt-2">Nomor : <span contenteditable="true" data-eid="115">{{ $payment->no_spm }}</span></p></div>
            <p class="text-justify leading-relaxed text-[10pt] mb-4">Sehubungan dengan Surat Perintah Membayar (SPM-LS) nomor <span class="font-bold">{{ $payment->no_spm }}</span> tanggal {{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }} yang saya ajukan sebesar Rp. <span contenteditable="true" data-eid="116" x-text="new Intl.NumberFormat('id-ID').format(nilaiKontrak)"></span> (<span x-text="terbilangTeks.toLowerCase()"></span>) untuk keperluan SKPD/ UNIT SKPD Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara Tahun Anggaran 2026 dengan ini menyatakan dengan sebenarnya bahwa:</p>
            <ol class="list-decimal ml-8 space-y-2 text-[10pt] text-justify leading-relaxed mb-4">
                <li>Saya bertanggung jawab secara penuh atas penggunaan (LS) tersebut diatas yang mengakibatkan pengeluaran atas beban anggaran belanja dan/atau pengeluaran pembiayaan sesuai dengan ketentuan peraturan perundang-undangan.</li>
                <li>Jumlah (LS) tersebut diatas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan saya laksanakan sesuai DPA/ DPPA-SKPD/ UNIT SKPD.</li>
                <li>Jumlah (LS) tersebut diatas tidak akan saya gunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku harus dilakukan dengan pembayaran lainnya.</li>
                <li>Apabila dikemudian hari terdapat kelebihan pembayaran atas belanja tersebut, saya bersedia untuk menyetor kelebihannya ke kas umum daerah.</li>
            </ol>
            <p class="text-justify text-[10pt] leading-relaxed mb-8">Demikian Surat Pernyataan ini dibuat untuk melengkapi persyaratan SPM-LS SKPD/UNIT SKPD saya. </p><br><br><br>
            <div class="grid grid-cols-2 text-center gap-4 px-4 text-[10pt] leading-tight mt-8">
                <div class="flex flex-col items-center">
                    <br>
                    <p class="font-bold uppercase mt-2 text-center">Pejabat Pelaksana Teknis Kegiatan <br><br></p>
                    <div class="mt-24"><p class="font-bold underline uppercase text-center" contenteditable="true" data-eid="117">Frans Agustinus Siahaan</p><p class="text-center">NIP. <span contenteditable="true" data-eid="118">197908222010011022</span></p></div>
                </div>
                <div class="flex flex-col items-center">
                    <p>Jakarta, <span contenteditable="true" data-eid="119">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p>
                    <p class="font-bold uppercase mt-2 text-center">Kepala Suku Dinas Sumber Daya Air<br>Kota Administrasi Jakarta Utara</p>
                    <div class="mt-24"><p class="font-bold underline uppercase text-center" contenteditable="true" data-eid="120">HERIA SUWANDI</p><p class="text-center">NIP. <span contenteditable="true" data-eid="121">197101272006041009</span></p></div>
                </div>
            </div>
        </div>

           <!-- PAGE 14: VERIFIKASI PPK (SPP GU) -->

        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-8 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center"><h1 class="text-[12pt] font-bold leading-tight uppercase text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1><h2 class="text-[14pt] font-bold leading-tight uppercase text-center">DINAS SUMBER DAYA AIR</h2><h3 class="text-[12pt] font-bold leading-tight uppercase text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3></div>
            </div>
            <div class="text-center mb-8"><h1 class="text-[12pt] font-black uppercase underline leading-tight text-center">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK UP/LS</h1><p class="font-bold mt-2">Nomor : <span contenteditable="true" data-eid="122">{{ $payment->no_spm }}</span></p></div>
            <p class="text-justify leading-relaxed text-[10pt] mb-4">Sehubungan dengan Surat Perintah Membayar (SPM-LS) nomor <span class="font-bold">{{ $payment->no_spm }}</span> tanggal {{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }} yang saya ajukan sebesar Rp. <span contenteditable="true" data-eid="123" x-text="new Intl.NumberFormat('id-ID').format(nilaiKontrak)"></span> (<span x-text="terbilangTeks.toLowerCase()"></span>) untuk keperluan SKPD/ UNIT SKPD Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara Tahun Anggaran 2026 dengan ini menyatakan dengan sebenarnya bahwa:</p>
            <ol class="list-decimal ml-8 space-y-2 text-[10pt] text-justify leading-relaxed mb-4">
                <li>Saya bertanggung jawab secara penuh atas penggunaan (LS) tersebut diatas yang mengakibatkan pengeluaran atas beban anggaran belanja dan/atau pengeluaran pembiayaan sesuai dengan ketentuan peraturan perundang-undangan.</li>
                <li>Jumlah (LS) tersebut diatas akan dipergunakan untuk keperluan guna membiayai kegiatan yang akan saya laksanakan sesuai DPA/ DPPA-SKPD/ UNIT SKPD.</li>
                <li>Jumlah (LS) tersebut diatas tidak akan saya gunakan untuk membiayai pengeluaran-pengeluaran yang menurut ketentuan yang berlaku harus dilakukan dengan pembayaran lainnya.</li>
                <li>Apabila dikemudian hari terdapat kelebihan pembayaran atas belanja tersebut, saya bersedia untuk menyetor kelebihannya ke kas umum daerah.</li>
            </ol>
            <p class="text-justify text-[10pt] leading-relaxed mb-8">Demikian Surat Pernyataan ini dibuat untuk melengkapi persyaratan SPM-LS SKPD/UNIT SKPD saya. </p><br><br><br>
            <div class="grid grid-cols-2 text-center gap-4 px-4 text-[10pt] leading-tight mt-8">
                <div></div> <!-- Kolom kosong di kiri -->
                <div class="flex flex-col items-center">
                    <p>Jakarta, <span contenteditable="true" data-eid="124">{{ $payment->tgl_spm ? $payment->tgl_spm->translatedFormat('d F Y') : '-' }}</span></p>
                    <p class="font-bold uppercase mt-2 text-center">Kepala Suku Dinas Sumber Daya Air<br>Kota Administrasi Jakarta Utara</p>
                    <div class="mt-24"><p class="font-bold underline uppercase text-center" contenteditable="true" data-eid="125">HERIA SUWANDI</p><p class="text-center">NIP. <span contenteditable="true" data-eid="126">197101272006041009</span></p></div>
                </div>
            </div>
        </div>

        
    
        <!-- PAGE 15: VERIFIKASI PPK (SPP LS) -->
        <div class="print-area page-break font-serif">
            <div class="flex items-center border-b-[3px] border-black pb-2 mb-8 text-center">
                <div class="w-[110px] pr-4"><img src="{{ asset('assets/logo.png') }}" class="w-full"></div>
                <div class="flex-1 text-center">
                    <h1 class="text-[12pt] font-bold leading-tight uppercase text-center text-center">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA</h1>
                    <h2 class="text-[14pt] font-bold leading-tight uppercase text-center text-center">DINAS SUMBER DAYA AIR</h2>
                    <h3 class="text-[12pt] font-bold leading-tight uppercase text-center text-center">SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA UTARA</h3>
                    <p class="text-[9pt] leading-tight mt-1 font-sans text-center">Jl. Yos Sudarso No. 27- 29 Telp. / Fax 43902028 Jakarta 14320</p>
                </div>
            </div>
            <div class="text-center mb-6 text-center"><h1 class="text-[11pt] font-black uppercase underline leading-tight text-center">SURAT PERNYATAAN VERIFIKASI PPK</h1><h1 class="text-[11pt] font-black uppercase underline leading-tight text-center">ATAS KELENGKAPAN DAN KEABSAHAN DOKUMEN DAN LAMPIRAN SPP GU</h1><p class="font-bold mt-2 text-center text-center">Nomor : <span contenteditable="true" data-eid="127">{{ $payment->no_spp }}</span></p></div>
            <p class="mb-4 text-[10.5pt]">Saya yang bertanda tangan dibawah ini:</p>
            <div class="grid grid-cols-[100px_10px_1fr] gap-y-1 mb-6 ml-4 text-[10.5pt]">
                <span>Nama</span><span>:</span><span class="font-bold uppercase" contenteditable="true" data-eid="128">Deny Tri Hendarto</span>
                <span>NIP</span><span>:</span><span contenteditable="true" data-eid="129">198111092010011017</span>
                <span>Jabatan</span><span>:</span><span contenteditable="true" data-eid="130" class="font-bold">Kepala Sub Bagian Tata Usaha</span>
                <span>Unit Kerja</span><span>:</span><span class="font-bold">Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara</span>
            </div>
            <p class="text-justify leading-relaxed text-[10.5pt] mb-4">Berdasarkan pengajuan Surat Permintaan Pembayaran GU Nomor <span class="font-bold">{{ $payment->no_spp }}</span> Tanggal {{ $payment->tgl_spp ? $payment->tgl_spp->translatedFormat('d F Y') : '-' }} telah dilakukan verifikasi terhadap kelengkapan dokumen pendukung sesuai dengan checklist terlampir. Atas surat permintaan pembayaran dan kelengkapan dokumen sebagaimana dimaksud dinyatakan lengkap dan sah sesuai peraturan perundang-undangan untuk dapat diproses sebagai persyaratan Perintah Membayar yang dituangkan dalam surat Surat Perintah Membayar. Jika dikemudian hari pernyataan saya ini tidak benar, maka saya bersedia diberikan sanksi sesuai peraturan yang berlaku.</p>
            <p class="text-justify text-[10.5pt] leading-relaxed mb-12">Demikian Surat ini saya buat dalam keadaan sadar dan tanpa paksaan dari pihak manapun.</p>
            <div class="flex flex-col items-end mr-4"><div class="text-center min-w-[350px] text-[10.5pt]"><p>Jakarta, <span contenteditable="true" data-eid="131">{{ $payment->tgl_spp ? $payment->tgl_spp->translatedFormat('d F Y') : '-' }}</span></p><p class="mt-1 font-bold text-center">Pejabat Penatausahaan Keuangan</p><div class="mt-32"><p class="font-bold underline uppercase text-center text-center" contenteditable="true" data-eid="132">Deny Tri Hendarto</p><p class="text-center text-center">NIP. <span contenteditable="true" data-eid="133">198111092010011017</span></p></div></div></div>
        </div>

      

    </div>

<script>
    lucide.createIcons();
    window.addEventListener('click', () => setTimeout(() => lucide.createIcons(), 50));

    // Tambahkan penomoran halaman secara otomatis di setiap halaman cetak
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.print-area').forEach((page, index) => {
            const pageNum = document.createElement('div');
            // Posisi absolut di tengah bawah kertas
            pageNum.className = 'absolute bottom-[10mm] left-0 right-0 text-center text-[10pt] font-sans font-bold text-slate-800';
            pageNum.innerHTML = `- ${index + 1} -`;
            page.appendChild(pageNum);
        });
    });
</script>
</body>
</html>
