<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Imports\DataKeuanganImport;
use App\Exports\DataKeuanganExport;
use App\Exports\TemplateImportExport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index()
    {
        $defaultFile = 'assets/DATABASE SPP SPM 2026.xlsx';
        $fileExists = File::exists(public_path($defaultFile));
        
        return view('import', [
            'defaultFile' => $defaultFile,
            'fileExists' => $fileExists
        ]);
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateImportExport, 'TEMPLATE_IMPORT_KEUANGAN.xlsx');
    }

    public function export()
    {
        return Excel::download(new DataKeuanganExport, 'DATA_KEUANGAN_EXPORT_' . date('Y-m-d') . '.xlsx');
    }

    public function process(Request $request)
    {
        try {
            $filePath = public_path('assets/DATABASE SPP SPM 2026.xlsx');
            
            if (!File::exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan di folder assets.');
            }

            Excel::import(new DataKeuanganImport, $filePath);

            return redirect()->route('dashboard')->with('success', 'Data berhasil diimport ke dalam database.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}
