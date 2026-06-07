<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Slip Gaji (Checklist SPP)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            page-break-after: always;
        }
        [contenteditable="true"]:focus { outline: 2px solid #3b82f6; background: #eff6ff; border-radius: 4px; }
    </style>
</head>
<body class="antialiased text-slate-800" x-data="{ 
    checklistGaji: [
        { no: 1, label: 'SPM LS GAJI /TKD', status: true, note: '' },
        { no: 2, label: 'KWITANSI', status: true, note: '' },
        { no: 3, label: 'FORM 33 ( Surat Permintaan Pembayaran LS Gaji dan Tunjangan)', status: true, note: '' },
        { no: 4, label: 'FORM 33 (Surat Pernyataan Tanggung Jawab LS)', status: true, note: '' },
        { no: 5, label: 'Listing Gaji /Rekap Daftar Gaji', status: true, note: '' },
        { no: 6, label: 'Rekap Potongan', status: true, note: '' },
        { no: 7, label: 'SPD', status: true, note: '' },
        { no: 8, label: 'DPA', status: true, note: '' }
    ],
    checklistUP: [
        { no: 1, label: 'Dokumen Pelaksanaan Anggaran (DPA)', status: true, note: '' },
        { no: 2, label: 'Surat Penyediaan Dana (SPD)', status: true, note: '' },
        { no: 3, label: 'Kwitansi bermaterai yang ditandatangani oleh pihak ketiga, PPTK, Bendahara dan disetujui/ditandatangani oleh PA/KPA', status: true, note: '' },
        { no: 4, label: 'Surat Pernyataan Tanggung Jawab Pengajuan SPP UP', status: true, note: '' },
        { no: 5, label: 'Surat Pengesahan Pertanggung jawaban Belanja UP', status: true, note: '' },
        { no: 6, label: 'Tanda Terima Penyampaian Laporan Pertanggung Jawaban UP dari Fungsional PPKD', status: true, note: '' },
        { no: 7, label: 'Rekapitulasi Atas penyetoran PPN, PPH yang ditanda tangani oleh Bendahara Pengeluaran/Bendahara Pengeluaran Pembantu yang di ketahui PA/KPA', status: true, note: '' }
    ],
    checklistGU: [
        { no: 1, label: 'Surat Permintaan Pembayaran Ganti Uang Persediaan (SPP-GU)', status: true, note: '' },
        { no: 2, label: 'Checklist Persyaratan Penerbitan SPP-GU yang ditandatangani PPK SKPD/UKPD', status: true, note: '' }
    ]
}">
    <div class="no-print bg-white p-4 shadow-sm border-b border-slate-200 flex justify-between items-center sticky top-0 z-50">
        <div>
            <h1 class="text-lg font-bold">Cetak Slip Gaji (Checklist SPP)</h1>
            <p class="text-xs text-slate-500">Total: {{ count($pptks) }} PPTK</p>
        </div>
        <button onclick="window.print()" class="px-6 py-2 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-700 transition-all text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Sekarang
        </button>
    </div>

    <div class="print-container">
        @foreach($pptks as $index => $pptk)
        
        <!-- PAGE 1a: CHECKLIST SPP GAJI -->
        <div class="print-area font-serif {{ $index > 0 ? 'page-break' : '' }}">
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
                            <td class="border border-black px-2 py-1 leading-tight" contenteditable="true" x-text="item.label"></td>
                            <td class="border border-black px-2 py-1 text-center cursor-pointer" @click="item.status = !item.status; if(!item.status) item.note = ''; else if(!item.note) item.note = '100%';"><span class="text-[12pt] font-black" x-text="item.status ? '√' : '-'"></span></td>
                            <td class="border border-black px-2 py-1" contenteditable="true" x-text="item.note"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <p class="mt-4 text-justify text-[9.5pt] leading-relaxed">Atas penatausahaan dan pengarsipan dokumen tersebut sepenuhnya menjadi tanggung-jawab kami dan dokumen sesuai sebagai persyaratan untuk pengajuan perintah membayar.</p>
            <div class="mt-8 grid grid-cols-2 text-center gap-10">
                <div class="flex flex-col items-center">
                    <p class="text-[9pt]">Jakarta, <span contenteditable="true">{{ $tgl_cetak->translatedFormat('d F Y') }}</span></p>
                    <p class="font-bold uppercase mt-2 text-[9pt] leading-tight">PEJABAT PELAKSANA TEKNIS KEGIATAN<br>(PPTK)</p>
                    <div class="mt-20">
                        <p class="font-bold underline uppercase text-[10pt]" contenteditable="true">{{ $pptk->nama }}</p>
                        <p class="text-[9pt]">NIP. <span contenteditable="true">{{ $pptk->nip }}</span></p>
                    </div>
                </div>
                <div class="flex flex-col items-center pt-[22px]">
                    <p class="font-bold uppercase text-[9pt] leading-tight text-center">KEPALA SUB. BAGIAN TATA USAHA<br>SUDIN SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-[10pt]" contenteditable="true">Deny Tri Hendarto</p><p class="text-[9pt]">NIP. <span contenteditable="true">198111092010011017</span></p></div>
                </div>
            </div>
        </div>

        <!-- PAGE 1b: CHECKLIST SPP UP -->
        <div class="print-area font-serif page-break">
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
                            <td class="border border-black px-2 py-1 leading-tight" contenteditable="true" x-text="item.label"></td>
                            <td class="border border-black px-2 py-1 text-center cursor-pointer" @click="item.status = !item.status; if(!item.status) item.note = ''; else if(!item.note) item.note = '100%';"><span class="text-[12pt] font-black" x-text="item.status ? '√' : '-'"></span></td>
                            <td class="border border-black px-2 py-1" contenteditable="true" x-text="item.note"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
             <div class="mt-8 grid grid-cols-2 text-center gap-10">
                <div class="flex flex-col items-center">
                    <!-- Kolom kiri kosong -->
                </div>
                <div class="flex flex-col items-center">
                    <p class="text-[9pt]">Jakarta, <span contenteditable="true">{{ $tgl_cetak->translatedFormat('d F Y') }}</span></p>
                    <p class="font-bold uppercase mt-2 text-[9pt] leading-tight text-center">KEPALA SUB. BAGIAN TATA USAHA<br>SUDIN SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA (PPK)</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-[10pt]" contenteditable="true">Deny Tri Hendarto</p><p class="text-[9pt]">NIP. <span contenteditable="true">198111092010011017</span></p></div>
                </div>
            </div>
        </div>

        <!-- PAGE 1c: CHECKLIST SPP GU -->
        <div class="print-area font-serif page-break">
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
                            <td class="border border-black px-2 py-1 leading-tight" contenteditable="true" x-text="item.label"></td>
                            <td class="border border-black px-2 py-1 text-center cursor-pointer" @click="item.status = !item.status; if(!item.status) item.note = ''; else if(!item.note) item.note = '100%';"><span class="text-[12pt] font-black" x-text="item.status ? '√' : '-'"></span></td>
                            <td class="border border-black px-2 py-1" contenteditable="true" x-text="item.note"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
             <div class="mt-8 grid grid-cols-2 text-center gap-10">
                <div class="flex flex-col items-center">
                    <!-- Kolom kiri kosong -->
                </div>
                <div class="flex flex-col items-center">
                    <p class="text-[9pt]">Jakarta, <span contenteditable="true">{{ $tgl_cetak->translatedFormat('d F Y') }}</span></p>
                    <p class="font-bold uppercase mt-2 text-[9pt] leading-tight text-center">KEPALA SUKU DINAS SUMBER DAYA AIR<br>KOTA ADMINISTRASI JAKARTA UTARA (PA/KPA)</p>
                    <div class="mt-20"><p class="font-bold underline uppercase text-[10pt]" contenteditable="true">Heria Suwandi</p><p class="text-[9pt]">NIP. <span contenteditable="true">197101272006041009</span></p></div>
                </div>
            </div>
        </div>
        
        @endforeach
    </div>
    
    @if(count($pptks) > 0)
    <script>
        // Otomatis muncul dialog print setelah halaman selesai diload
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
    @endif
</body>
</html>
