<?php

namespace App\Http\Controllers;

use App\Models\EmployeeBonusSetting;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;

class BonusReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $employeeId = $request->input('employee_id');

        $employees = User::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
                'role',
            ]);

        $query = Rental::query()
            ->with([
                'employee',
                'customer',
            ])
            ->whereNotNull('employee_id');

        // Filter tanggal transaksi
        if ($startDate) {
            $query->whereDate('rental_start', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('rental_start', '<=', $endDate);
        }

        // Filter karyawan
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $rentals = $query
            ->orderByDesc('rental_start')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ambil setting bonus
        |--------------------------------------------------------------------------
        */
        $settings = EmployeeBonusSetting::query()
            ->where('active', true)
            ->get()
            ->keyBy('employee_id');

        /*
        |--------------------------------------------------------------------------
        | Hitung bonus setiap transaksi
        |--------------------------------------------------------------------------
        */
        $details = $rentals->map(function ($rental) use ($settings) {

            $setting = $settings->get($rental->employee_id);

            $transactionValue = (float) $rental->total;
            $bonus = 0;

            $bonusType = null;
            $bonusValue = 0;

            if ($setting) {

                $bonusType = $setting->bonus_type;
                $bonusValue = (float) $setting->bonus_value;

                if ($setting->bonus_type === 'percentage') {

                    $bonus = $transactionValue
                        * ($bonusValue / 100);

                } elseif ($setting->bonus_type === 'per_transaction') {

                    $bonus = $bonusValue;
                }
            }

            return [
                'rental_id' => $rental->id,
                'rental_code' => $rental->rental_code,

                'rental_start' => $rental->rental_start?->format('Y-m-d H:i'),

                'employee_id' => $rental->employee_id,

                'employee_name' =>
                    $rental->employee?->name ?? '-',

               'customer_name' =>
                      $rental->customer?->name ?? '-',
                      
                'transaction_value' => $transactionValue,

                'bonus_type' => $bonusType,

                'bonus_value' => $bonusValue,

                'bonus_amount' => round($bonus, 2),
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | Rekap per karyawan
        |--------------------------------------------------------------------------
        */
        $summary = $details
            ->groupBy('employee_id')
            ->map(function ($items) {

                return [
                    'employee_id' =>
                        $items->first()['employee_id'],

                    'employee_name' =>
                        $items->first()['employee_name'],

                    'transaction_count' =>
                        $items->count(),

                    'transaction_total' =>
                        $items->sum('transaction_value'),

                    'bonus_total' =>
                        $items->sum('bonus_amount'),
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Grand total
        |--------------------------------------------------------------------------
        */
        $grandTotal = [
            'transaction_count' =>
                $details->count(),

            'transaction_total' =>
                $details->sum('transaction_value'),

            'bonus_total' =>
                $details->sum('bonus_amount'),
        ];

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'summary' => $summary,

                'details' => $details->values(),

                'grand_total' => $grandTotal,

                'employees' => $employees->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'email' => $employee->email,
                        'role' => $employee->role,
                    ];
                })->values(),
            ]);
        }

        return view('bonuses.report', [
            'employees' => $employees,
        ]);
    }
}