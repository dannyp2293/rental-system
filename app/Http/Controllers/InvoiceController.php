<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Menampilkan invoice berdasarkan rental.
     */
    public function show(Rental $rental)
    {
        $rental->load([
            'customer',
            'employee',
            'items.product',
            'payments',
        ]);

        // Total pembayaran yang sudah masuk
        $paid = $rental->payments->sum('amount');

        // Sisa tagihan
        $remaining = max(
            0,
            (float) $rental->total - (float) $paid
        );

        // Status pembayaran
        if ($paid <= 0) {
            $paymentStatus = 'UNPAID';
        } elseif ($paid < $rental->total) {
            $paymentStatus = 'PARTIAL';
        } else {
            $paymentStatus = 'PAID';
        }

        return view('invoices.show', [
            'rental' => $rental,
            'paid' => $paid,
            'remaining' => $remaining,
            'paymentStatus' => $paymentStatus,
        ]);
    }
}