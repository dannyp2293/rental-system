<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Rental::with([
            'customer',
            'payments',
        ])->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('rental_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('customer_code', 'like', "%{$search}%");
                    });
            });
        }

        $rentals = $query->paginate(10)->withQueryString();

        $data = $rentals->getCollection()->map(function ($rental) {
            $paid = (float) $rental->payments->sum('amount');
            $total = (float) $rental->total;
            $remaining = max(0, $total - $paid);

            if ($paid <= 0) {
                $status = 'unpaid';
            } elseif ($paid < $total) {
                $status = 'partial';
            } else {
                $status = 'paid';
            }

            return [
                'id' => $rental->id,
                'rental_code' => $rental->rental_code,
                'customer' => $rental->customer?->name,
                'total' => $total,
                'paid' => $paid,
                'remaining' => $remaining,
                'status' => $status,
                'payments' => $rental->payments,
            ];
        });

        if ($request->ajax()) {
            return response()->json([
                'data' => $data,
                'current_page' => $rentals->currentPage(),
                'last_page' => $rentals->lastPage(),
                'total' => $rentals->total(),
            ]);
        }

        return view('payments.index', compact('rentals', 'data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rental_id' => [
                'required',
                'integer',
                'exists:rentals,id',
            ],

            'paid_at' => [
                'required',
                'date',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'method' => [
                'required',
                'in:cash,transfer,qris,debit,e_wallet',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        try {
            $payment = DB::transaction(function () use ($validated) {

                $rental = Rental::with('payments')
                    ->lockForUpdate()
                    ->findOrFail($validated['rental_id']);

                $paidBefore = (float) $rental->payments->sum('amount');
                $total = (float) $rental->total;
                $amount = (float) $validated['amount'];

                if ($paidBefore >= $total) {
                    throw new \Exception(
                        'Rental ini sudah lunas.'
                    );
                }

                $remaining = $total - $paidBefore;

                if ($amount > $remaining) {
                    throw new \Exception(
                        'Jumlah pembayaran melebihi sisa tagihan. Sisa tagihan: Rp ' .
                        number_format($remaining, 0, ',', '.')
                    );
                }

                $paymentCode = 'PAY-' . now()->format('YmdHis') . '-' .
                    str_pad((string) ($rental->id), 4, '0', STR_PAD_LEFT);

                $payment = Payment::create([
                    'rental_id' => $rental->id,
                    'payment_code' => $paymentCode,
                    'paid_at' => $validated['paid_at'],
                    'amount' => $amount,
                    'method' => $validated['method'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                return $payment->load([
                    'rental.customer',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disimpan.',
                'data' => $payment,
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function destroy(Payment $payment)
    {
        try {

            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dihapus.',
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}