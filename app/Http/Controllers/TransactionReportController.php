<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionReportController extends Controller
{
    /**
     * Laporan transaksi rental.
     */
    public function index(Request $request)
    {
        $query = Rental::query()
            ->with([
                'customer',
                'employee',
                'courier',
                'items.product',
            ])
            ->withSum('payments', 'amount')
            ->latest('rental_start');

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('rental_code', 'like', "%{$search}%")

                    ->orWhereHas('customer', function ($customer) use ($search) {

                        $customer
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere(
                                'customer_code',
                                'like',
                                "%{$search}%"
                            );
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {
            $query->whereDate(
                'rental_start',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $query->whereDate(
                'rental_start',
                '<=',
                $request->date_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS RENTAL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('rental_status')) {
            $query->where(
                'status',
                $request->rental_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $paymentStatus = $request->payment_status;

            $query->where(function ($q) use ($paymentStatus) {

                $paidSubquery = DB::table('payments')
                    ->selectRaw('COALESCE(SUM(amount), 0)')
                    ->whereColumn(
                        'payments.rental_id',
                        'rentals.id'
                    );

                if ($paymentStatus === 'unpaid') {

                    $q->whereRaw(
                        "({$paidSubquery->toSql()}) = 0",
                        $paidSubquery->getBindings()
                    );

                } elseif ($paymentStatus === 'partial') {

                    $q->whereRaw(
                        "({$paidSubquery->toSql()}) > 0
                         AND ({$paidSubquery->toSql()}) < rentals.total",
                        array_merge(
                            $paidSubquery->getBindings(),
                            $paidSubquery->getBindings()
                        )
                    );

                } elseif ($paymentStatus === 'paid') {

                    $q->whereRaw(
                        "({$paidSubquery->toSql()}) >= rentals.total",
                        $paidSubquery->getBindings()
                    );
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $rentals = $query
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | HITUNG DATA LAPORAN
        |--------------------------------------------------------------------------
        */

        $data = $rentals->getCollection()->map(function ($rental) {

            $total = (float) $rental->total;

            $paid = (float) ($rental->payments_sum_amount ?? 0);

            $remaining = max(
                0,
                $total - $paid
            );

            if ($paid <= 0) {
                $paymentStatus = 'UNPAID';
            } elseif ($paid < $total) {
                $paymentStatus = 'PARTIAL';
            } else {
                $paymentStatus = 'PAID';
            }

            return [
                'id' => $rental->id,

                'rental_code' => $rental->rental_code,

                'rental_start' => optional(
                    $rental->rental_start
                )->format('Y-m-d H:i:s'),

                'rental_end' => optional(
                    $rental->rental_end
                )->format('Y-m-d H:i:s'),

                'returned_at' => optional(
                    $rental->returned_at
                )->format('Y-m-d H:i:s'),

                'customer' => $rental->customer
                    ? $rental->customer->name
                    : '-',

                'customer_code' => $rental->customer
                    ? $rental->customer->customer_code
                    : '-',

                'employee' => $rental->employee
                    ? $rental->employee->name
                    : '-',

                'courier' => $rental->courier
                    ? $rental->courier->name
                    : '-',

                'subtotal' => (float) $rental->subtotal,

                'discount' => (float) $rental->discount,

                'deposit' => (float) $rental->deposit,

                'total' => $total,

                'paid' => $paid,

                'remaining' => $remaining,

                'rental_status' => strtoupper(
                    $rental->status
                ),

                'payment_status' => $paymentStatus,

                'notes' => $rental->notes,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | RESPONSE AJAX
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'data' => $data->values(),

                'current_page' => $rentals->currentPage(),

                'last_page' => $rentals->lastPage(),

                'per_page' => $rentals->perPage(),

                'total' => $rentals->total(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | HALAMAN
        |--------------------------------------------------------------------------
        */

        return view(
            'reports.transactions',
            compact('rentals')
        );
    }
}