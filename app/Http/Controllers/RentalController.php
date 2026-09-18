<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class RentalController extends Controller
{
    /**
     * Menampilkan daftar rental.
     */
    public function index(Request $request)
    {
        $query = Rental::with([
            'customer',
            'employee',
            'courier',
            'items.product',
        ])->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('rental_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customer) use ($search) {
                        $customer->where('name', 'like', "%{$search}%")
                            ->orWhere('customer_code', 'like', "%{$search}%");
                    });
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rentals = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'data' => $rentals->items(),
                'current_page' => $rentals->currentPage(),
                'last_page' => $rentals->lastPage(),
                'total' => $rentals->total(),
            ]);
        }

        $customers = \App\Models\Customer::orderBy('name')->get([
    'id',
    'customer_code',
    'name',
]);

$products = \App\Models\Product::with('category')
    ->orderBy('name')
    ->get([
        'id',
        'category_id',
        'code',
        'name',
        'price_per_day',
        'price_per_hour',
        'stock',
        'status',
    ]);

foreach ($products as $product) {

    $usedStock = RentalItem::where('product_id', $product->id)
        ->whereHas('rental', function ($query) {
            $query->whereIn('status', [
                'pending',
                'active',
            ]);
        })
        ->sum('quantity');

    $product->available_stock = max(
        0,
        $product->stock - $usedStock
    );

    $product->rented_stock = $usedStock;
}

$couriers = \App\Models\User::where('role', 'kurir')
    ->orderBy('name')
    ->get([
        'id',
        'name',
    ]);

return view('rentals.index', compact(
    'rentals',
    'customers',
    'products',
    'couriers'
));
    }

    /**
     * Membuat transaksi rental baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => [
                'required',
                'exists:customers,id',
            ],

            'courier_id' => [
                'nullable',
                'exists:users,id',
            ],

            'rental_start' => [
                'required',
                'date',
            ],

            'rental_end' => [
                'required',
                'date',
                'after:rental_start',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'deposit' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'active',
                ]),
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

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.pricing_type' => [
                'required',
                Rule::in([
                    'hour',
                    'day',
                ]),
            ],
        ]);

        try {
            $rental = DB::transaction(function () use ($validated) {

                $start = Carbon::parse($validated['rental_start']);
                $end = Carbon::parse($validated['rental_end']);

                $discount = (float) ($validated['discount'] ?? 0);
                $deposit = (float) ($validated['deposit'] ?? 0);

                /*
                |--------------------------------------------------------------------------
                | Buat rental terlebih dahulu
                |--------------------------------------------------------------------------
                */

                $rental = Rental::create([
                    'rental_code' => 'TMP-' . Str::uuid(),
                    'customer_id' => $validated['customer_id'],
                    'employee_id' => auth()->id(),
                    'courier_id' => $validated['courier_id'] ?? null,
                    'rental_start' => $start,
                    'rental_end' => $end,
                    'subtotal' => 0,
                    'discount' => $discount,
                    'deposit' => $deposit,
                    'total' => 0,
                    'status' => $validated['status'] ?? 'pending',
                    'notes' => $validated['notes'] ?? null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Generate kode rental berdasarkan ID
                |--------------------------------------------------------------------------
                */

                $rental->update([
                    'rental_code' => 'RNT-' . str_pad(
                        $rental->id,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ),
                ]);

                $subtotal = 0;

                /*
                |--------------------------------------------------------------------------
                | Hitung durasi
                |--------------------------------------------------------------------------
                */

                $totalHours = max(
                    1,
                    (int) ceil(
                        $start->diffInMinutes($end) / 60
                    )
                );

                $totalDays = max(
                    1,
                    (int) ceil(
                        $start->diffInMinutes($end) / 1440
                    )
                );

                /*
                |--------------------------------------------------------------------------
                | Process setiap produk
                |--------------------------------------------------------------------------
                */

                foreach ($validated['items'] as $item) {

                    /*
                    |--------------------------------------------------------------------------
                    | Lock product agar stok lebih aman ketika transaksi bersamaan
                    |--------------------------------------------------------------------------
                    */

                    $product = Product::where('id', $item['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    // Produk maintenance tidak boleh disewa
                    if ($product->status === 'maintenance') {
                        throw new \Exception(
                            "Produk {$product->name} sedang maintenance."
                        );
                    }

                    $quantity = (int) $item['quantity'];

                    /*
                    |--------------------------------------------------------------------------
                    | Hitung stok yang sedang dipakai / dipesan
                    |--------------------------------------------------------------------------
                    */

                    $usedStock = RentalItem::where('product_id', $product->id)
                        ->whereHas('rental', function ($query) {
                            $query->whereIn('status', [
                                'pending',
                                'active',
                            ]);
                        })
                        ->sum('quantity');

                    $availableStock = $product->stock - $usedStock;

                    if ($quantity > $availableStock) {
                        throw new \Exception(
                            "Stok {$product->name} tidak mencukupi. " .
                            "Tersedia: {$availableStock}."
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Tentukan harga
                    |--------------------------------------------------------------------------
                    */

                    if ($item['pricing_type'] === 'hour') {

                        $unitPrice = (float) $product->price_per_hour;
                        $duration = $totalHours;

                    } else {

                        $unitPrice = (float) $product->price_per_day;
                        $duration = $totalDays;
                    }

                    $itemSubtotal = $unitPrice
                        * $duration
                        * $quantity;

                    RentalItem::create([
                        'rental_id' => $rental->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'pricing_type' => $item['pricing_type'],
                        'unit_price' => $unitPrice,
                        'duration' => $duration,
                        'subtotal' => $itemSubtotal,
                    ]);

                    $subtotal += $itemSubtotal;
                }

                /*
                |--------------------------------------------------------------------------
                | Hitung total
                |--------------------------------------------------------------------------
                */

                $total = max(
                    0,
                    $subtotal - $discount
                );

                $rental->update([
                    'subtotal' => $subtotal,
                    'total' => $total,
                ]);

                return $rental->fresh([
                    'customer',
                    'employee',
                    'courier',
                    'items.product',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi rental berhasil dibuat.',
                'data' => $rental,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update transaksi rental.
     */
    public function update(Request $request, Rental $rental)
    {
        // Rental yang sudah dikembalikan / dibatalkan
        // tidak boleh diedit sembarangan.
        if (in_array($rental->status, ['returned', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Rental yang sudah returned/cancelled tidak dapat diedit.',
            ], 422);
        }

        $validated = $request->validate([
            'customer_id' => [
                'required',
                'exists:customers,id',
            ],

            'courier_id' => [
                'nullable',
                'exists:users,id',
            ],

            'rental_start' => [
                'required',
                'date',
            ],

            'rental_end' => [
                'required',
                'date',
                'after:rental_start',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'deposit' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'active',
                ]),
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

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.pricing_type' => [
                'required',
                Rule::in([
                    'hour',
                    'day',
                ]),
            ],
        ]);

        try {

            $rental = DB::transaction(function () use (
                $validated,
                $rental
            ) {

                $start = Carbon::parse($validated['rental_start']);
                $end = Carbon::parse($validated['rental_end']);

                $discount = (float) ($validated['discount'] ?? 0);
                $deposit = (float) ($validated['deposit'] ?? 0);

                /*
                |--------------------------------------------------------------------------
                | Hapus item lama
                |--------------------------------------------------------------------------
                */

                $rental->items()->delete();

                $subtotal = 0;

                $totalHours = max(
                    1,
                    (int) ceil(
                        $start->diffInMinutes($end) / 60
                    )
                );

                $totalDays = max(
                    1,
                    (int) ceil(
                        $start->diffInMinutes($end) / 1440
                    )
                );

                /*
                |--------------------------------------------------------------------------
                | Buat item baru
                |--------------------------------------------------------------------------
                */

                foreach ($validated['items'] as $item) {

                    $product = Product::where('id', $item['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($product->status === 'maintenance') {
                        throw new \Exception(
                            "Produk {$product->name} sedang maintenance."
                        );
                    }

                    $quantity = (int) $item['quantity'];

                    /*
                    |--------------------------------------------------------------------------
                    | Stok rental lain
                    |--------------------------------------------------------------------------
                    */

                    $usedStock = RentalItem::where('product_id', $product->id)
                        ->where('rental_id', '!=', $rental->id)
                        ->whereHas('rental', function ($query) {
                            $query->whereIn('status', [
                                'pending',
                                'active',
                            ]);
                        })
                        ->sum('quantity');

                    $availableStock = $product->stock - $usedStock;

                    if ($quantity > $availableStock) {
                        throw new \Exception(
                            "Stok {$product->name} tidak mencukupi. " .
                            "Tersedia: {$availableStock}."
                        );
                    }

                    if ($item['pricing_type'] === 'hour') {

                        $unitPrice = (float) $product->price_per_hour;
                        $duration = $totalHours;

                    } else {

                        $unitPrice = (float) $product->price_per_day;
                        $duration = $totalDays;
                    }

                    $itemSubtotal = $unitPrice
                        * $duration
                        * $quantity;

                    $rental->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'pricing_type' => $item['pricing_type'],
                        'unit_price' => $unitPrice,
                        'duration' => $duration,
                        'subtotal' => $itemSubtotal,
                    ]);

                    $subtotal += $itemSubtotal;
                }

                $total = max(
                    0,
                    $subtotal - $discount
                );

                $rental->update([
                    'customer_id' => $validated['customer_id'],
                    'courier_id' => $validated['courier_id'] ?? null,
                    'rental_start' => $start,
                    'rental_end' => $end,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'deposit' => $deposit,
                    'total' => $total,
                    'status' => $validated['status'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                return $rental->fresh([
                    'customer',
                    'employee',
                    'courier',
                    'items.product',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi rental berhasil diperbarui.',
                'data' => $rental,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Hapus rental.
     */
    public function destroy(Rental $rental)
    {
        if (in_array($rental->status, ['active', 'returned'])) {
            return response()->json([
                'success' => false,
                'message' => 'Rental active/returned tidak dapat dihapus.',
            ], 422);
        }

        $rental->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rental berhasil dihapus.',
        ]);
    }
}