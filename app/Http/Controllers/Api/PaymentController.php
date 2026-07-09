<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Menampilkan semua data pembayaran (GET /api/payments)
     */
    public function index(Request $request)
    {
        $query = Payment::with([
            'vendor', 
            'contract', 
            'pptk', 
            'programRef', 
            'kegiatanRef'
        ]);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        }

        $payments = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data pembayaran berhasil diambil',
            'data' => $payments
        ]);
    }

    /**
     * Menampilkan detail satu data pembayaran (GET /api/payments/{id})
     */
    public function show($id)
    {
        $payment = Payment::with([
            'vendor', 
            'contract', 
            'pptk', 
            'programRef', 
            'kegiatanRef'
        ])->find($id);

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pembayaran tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail data pembayaran berhasil diambil',
            'data' => $payment
        ]);
    }
}
