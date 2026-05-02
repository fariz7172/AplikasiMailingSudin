<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pptk;
use App\Models\Vendor;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['pptk', 'vendor', 'contract']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('vendor', function($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%$search%");
            })->orWhere('no_spm', 'like', "%$search%")
              ->orWhere('no_sp2d', 'like', "%$search%")
              ->orWhere('program', 'like', "%$search%");
        }

        $payments = $query->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $pptk = Pptk::all();
        $vendors = Vendor::all();
        $contracts = Contract::all();
        return view('payments.create', compact('pptk', 'vendors', 'contracts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pptk_id' => 'nullable|exists:pptk,id',
            'nama_perusahaan' => 'nullable|string|max:255',
            'nomor_kontrak' => 'nullable|string|max:255',
            'nilai_kontrak' => 'nullable|numeric',
            'no_spm' => 'nullable|string|max:255',
            'tgl_spm' => 'nullable|date',
        ]);

        DB::transaction(function() use ($request) {
            // Update/Create Vendor
            $vendor = Vendor::updateOrCreate(
                ['nama_perusahaan' => $request->nama_perusahaan],
                $request->only(['direktur', 'npwp', 'akte', 'tgl_akte', 'tdp', 'tgl_tdp', 'bank', 'no_rekening', 'alamat', 'alamat_update'])
            );

            // Update/Create Contract
            $contract = Contract::updateOrCreate(
                ['nomor_kontrak' => $request->nomor_kontrak],
                $request->only(['tgl_kontrak', 'nilai_kontrak', 'terbilang_kontrak', 'addendum_kontrak', 'tgl_addendum', 'nilai_addendum1', 'addendum_kontrak2', 'tgl_addendum2', 'nilai_addendum2', 'jangka_waktu', 'tahun_tdp'])
            );

            // Create Payment
            Payment::create(array_merge($request->all(), [
                'vendor_id' => $vendor->id,
                'contract_id' => $contract->id
            ]));
        });

        return redirect()->route('payments.index')->with('success', 'Data lengkap berhasil disimpan.');
    }

    public function show(Payment $payment)
    {
        return redirect()->route('payments.edit', $payment);
    }

    public function edit(Payment $payment)
    {
        $pptk = Pptk::all();
        $vendors = Vendor::all();
        $contracts = Contract::all();
        return view('payments.edit', compact('payment', 'pptk', 'vendors', 'contracts'));
    }

    public function update(Request $request, Payment $payment)
    {
        DB::transaction(function() use ($request, $payment) {
            // Update Vendor terkait
            $payment->vendor->update($request->only([
                'nama_perusahaan', 'direktur', 'npwp', 'akte', 'tgl_akte', 
                'tdp', 'tgl_tdp', 'bank', 'no_rekening', 'alamat', 'alamat_update'
            ]));
            
            // Update Contract terkait
            $payment->contract->update($request->only([
                'nomor_kontrak', 'tgl_kontrak', 'nilai_kontrak', 'addendum_kontrak', 
                'tgl_addendum', 'nilai_addendum1', 'addendum_kontrak2', 'tgl_addendum2', 
                'nilai_addendum2', 'jangka_waktu', 'tahun_tdp'
            ]));

            // Update Payment
            $payment->update($request->all());
        });

        return redirect()->route('payments.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Data berhasil dihapus.');
    }

    public function print(Payment $payment)
    {
        $payment->load(['vendor', 'contract', 'pptk']);
        return view('payments.print', compact('payment'));
    }
}
