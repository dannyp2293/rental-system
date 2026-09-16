<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\RentalReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalReturnController extends Controller
{
    /**
     * Menampilkan daftar rental yang bisa dikembalikan.
     */
    public function index(Request $request)
    {
        $query = Rental::with([
            'customer',
            'courier',
            'items.product',
            'rentalReturn',
        ])
        ->whereIn('status', ['pending', 'active'])
        ->latest();

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

        $rentals = $query
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {

            return response()->json([
                'data' => $rentals->items(),
                'current_page' => $rentals->currentPage(),
                'last_page' => $rentals->lastPage(),
                'total' => $rentals->total(),
            ]);
        }

        return view('rental_returns.index', compact('rentals'));
    }


    /**
     * Menyimpan proses pengembalian.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'rental_id' => [
                'required',
                'integer',
                'exists:rentals,id',
            ],

            'returned_at' => [
                'required',
                'date',
            ],

            'late_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'damage_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'lost_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.rental_item_id' => [
                'required',
                'integer',
                'exists:rental_items,id',
            ],

            'items.*.condition' => [
                'required',
                'in:good,damaged,lost',
            ],

            'items.*.damage_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.lost_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.notes' => [
                'nullable',
                'string',
            ],

        ]);


        try {

            $result = DB::transaction(function () use ($validated) {

                /*
                |--------------------------------------------------------------------------
                | LOCK RENTAL
                |--------------------------------------------------------------------------
                */

                $rental = Rental::with([
                    'items.product',
                    'rentalReturn',
                ])
                ->lockForUpdate()
                ->findOrFail($validated['rental_id']);


                /*
                |--------------------------------------------------------------------------
                | CEK STATUS
                |--------------------------------------------------------------------------
                */

                if (!in_array($rental->status, ['pending', 'active'])) {

                    throw new \Exception(
                        'Rental ini tidak dapat diproses karena statusnya sudah ' .
                        $rental->status . '.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CEK SUDAH DIKEMBALIKAN
                |--------------------------------------------------------------------------
                */

                if ($rental->rentalReturn) {

                    throw new \Exception(
                        'Rental ini sudah memiliki data pengembalian.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | VALIDASI ITEM
                |--------------------------------------------------------------------------
                */

                $rentalItemIds = $rental
                    ->items
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->values()
                    ->all();


                $submittedItemIds = collect($validated['items'])
                    ->pluck('rental_item_id')
                    ->map(fn ($id) => (int) $id)
                    ->values()
                    ->all();


                /*
                |--------------------------------------------------------------------------
                | Pastikan tidak ada item rental yang bukan milik rental ini.
                |--------------------------------------------------------------------------
                */

                $invalidItems = array_diff(
                    $submittedItemIds,
                    $rentalItemIds
                );


                if (!empty($invalidItems)) {

                    throw new \Exception(
                        'Terdapat produk pengembalian yang tidak sesuai dengan rental.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Pastikan semua item rental dikirim.
                |--------------------------------------------------------------------------
                */

                $missingItems = array_diff(
                    $rentalItemIds,
                    $submittedItemIds
                );


                if (!empty($missingItems)) {

                    throw new \Exception(
                        'Semua produk dalam rental harus diproses pengembaliannya.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | HITUNG KETERLAMBATAN
                |--------------------------------------------------------------------------
                */

                $returnedAt = \Illuminate\Support\Carbon::parse(
                    $validated['returned_at']
                );


                $lateHours = 0;


                if (
                    $returnedAt->greaterThan(
                        $rental->rental_end
                    )
                ) {

                    $lateMinutes =
                        $rental
                            ->rental_end
                            ->diffInMinutes(
                                $returnedAt
                            );

                    $lateHours = (int) ceil(
                        $lateMinutes / 60
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | BIAYA
                |--------------------------------------------------------------------------
                */

                $lateFee = (float) (
                    $validated['late_fee'] ?? 0
                );


                $damageFee = collect(
                    $validated['items']
                )->sum(function ($item) {

                    return (float) (
                        $item['damage_fee'] ?? 0
                    );

                });


                $lostFee = collect(
                    $validated['items']
                )->sum(function ($item) {

                    return (float) (
                        $item['lost_fee'] ?? 0
                    );

                });


                /*
                |--------------------------------------------------------------------------
                | DEPOSIT
                |--------------------------------------------------------------------------
                */

                $deposit = (float) $rental->deposit;


                $totalDeduction =
                    $lateFee
                    + $damageFee
                    + $lostFee;


                $depositDeduction =
                    min(
                        $deposit,
                        $totalDeduction
                    );


                $depositReturn =
                    max(
                        0,
                        $deposit - $depositDeduction
                    );


                /*
                |--------------------------------------------------------------------------
                | BUAT DATA RETURN
                |--------------------------------------------------------------------------
                */

                $rentalReturn = RentalReturn::create([

                    'rental_id' =>
                        $rental->id,

                    'returned_at' =>
                        $returnedAt,

                    'late_hours' =>
                        $lateHours,

                    'late_fee' =>
                        $lateFee,

                    'damage_fee' =>
                        $damageFee,

                    'lost_fee' =>
                        $lostFee,

                    'deposit_deduction' =>
                        $depositDeduction,

                    'deposit_return' =>
                        $depositReturn,

                    'notes' =>
                        $validated['notes'] ?? null,

                ]);


                /*
                |--------------------------------------------------------------------------
                | BUAT DETAIL RETURN ITEM
                |--------------------------------------------------------------------------
                */

                foreach (
                    $validated['items']
                    as $item
                ) {

                    $rentalReturn
                        ->items()
                        ->create([

                            'rental_item_id' =>
                                $item['rental_item_id'],

                            'condition' =>
                                $item['condition'],

                            'damage_fee' =>
                                $item['damage_fee'] ?? 0,

                            'lost_fee' =>
                                $item['lost_fee'] ?? 0,

                            'notes' =>
                                $item['notes'] ?? null,

                        ]);

                }


                /*
                |--------------------------------------------------------------------------
                | UPDATE RENTAL
                |--------------------------------------------------------------------------
                */

                $rental->update([

                    'status' =>
                        'returned',

                    'returned_at' =>
                        $returnedAt,

                ]);


                return $rentalReturn
                    ->load([
                        'rental.customer',
                        'items.rentalItem.product',
                    ]);

            });


            return response()->json([

                'success' => true,

                'message' =>
                    'Pengembalian berhasil diproses.',

                'data' =>
                    $result,

            ]);

        } catch (\Throwable $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    $e->getMessage(),

            ], 422);
        }
    }
}