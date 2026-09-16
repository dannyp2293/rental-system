<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DEFAULT PERIODE
        |--------------------------------------------------------------------------
        */

        $dateFrom = $request->input(
            'date_from',
            now()->startOfMonth()->toDateString()
        );

        $dateTo = $request->input(
            'date_to',
            now()->toDateString()
        );


        /*
        |--------------------------------------------------------------------------
        | PAYMENT QUERY
        |--------------------------------------------------------------------------
        */

        $paymentQuery = Payment::query()
            ->whereDate('paid_at', '>=', $dateFrom)
            ->whereDate('paid_at', '<=', $dateTo);


        /*
        |--------------------------------------------------------------------------
        | TOTAL PENDAPATAN
        |--------------------------------------------------------------------------
        */

        $totalIncome = (clone $paymentQuery)->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | JUMLAH PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $totalPayments = (clone $paymentQuery)->count();


        /*
        |--------------------------------------------------------------------------
        | PENDAPATAN PER HARI
        |--------------------------------------------------------------------------
        */

        $daily = (clone $paymentQuery)
            ->selectRaw(
                'DATE(paid_at) as date, SUM(amount) as total'
            )
            ->groupBy(
                DB::raw('DATE(paid_at)')
            )
            ->orderBy(
                'date'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PENDAPATAN PER BULAN
        |--------------------------------------------------------------------------
        */

        $monthly = (clone $paymentQuery)
            ->selectRaw(
                'YEAR(paid_at) as year,
                 MONTH(paid_at) as month,
                 SUM(amount) as total'
            )
            ->groupBy(
                DB::raw('YEAR(paid_at)'),
                DB::raw('MONTH(paid_at)')
            )
            ->orderBy('year')
            ->orderBy('month')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PENDAPATAN PER TAHUN
        |--------------------------------------------------------------------------
        */

        $yearly = (clone $paymentQuery)
            ->selectRaw(
                'YEAR(paid_at) as year,
                 SUM(amount) as total'
            )
            ->groupBy(
                DB::raw('YEAR(paid_at)')
            )
            ->orderBy('year')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TOTAL NILAI RENTAL PADA PERIODE
        |--------------------------------------------------------------------------
        */

        $rentalQuery = Rental::query()
            ->whereDate('rental_start', '>=', $dateFrom)
            ->whereDate('rental_start', '<=', $dateTo);


        $totalRental = (clone $rentalQuery)->sum('total');


        /*
        |--------------------------------------------------------------------------
        | TOTAL PIUTANG
        |--------------------------------------------------------------------------
        |
        | Piutang dihitung dari rental pada periode yang dipilih.
        |
        */

        $rentals = (clone $rentalQuery)
            ->withSum('payments', 'amount')
            ->get();


        $totalRemaining = $rentals->sum(function ($rental) {

            $total = (float) $rental->total;

            $paid = (float) (
                $rental->payments_sum_amount ?? 0
            );

            return max(
                0,
                $total - $paid
            );
        });


        /*
        |--------------------------------------------------------------------------
        | AJAX RESPONSE
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'period' => [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                ],

                'summary' => [
                    'total_income' => (float) $totalIncome,
                    'total_payments' => $totalPayments,
                    'total_rental' => (float) $totalRental,
                    'total_remaining' => (float) $totalRemaining,
                ],

                'daily' => $daily,

                'monthly' => $monthly,

                'yearly' => $yearly,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'reports.income'
        );
    }
}