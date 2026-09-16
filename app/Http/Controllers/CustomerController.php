<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query()
            ->latest();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('customer_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");

            });
        }

        $customers = $query
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {

            return response()->json([
                'data' => $customers->items(),
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'total' => $customers->total(),
            ]);
        }

        return view('customers.index', compact('customers'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'whatsapp' => [
                'required',
                'string',
                'max:30',
            ],

            'guarantee_type' => [
                'nullable',
                Rule::in([
                    'ktp',
                    'sim',
                    'passport',
                    'other',
                ]),
            ],

            'guarantee_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);


        // Generate customer code
        $lastCustomer = Customer::latest('id')->first();

        $nextNumber = $lastCustomer
            ? $lastCustomer->id + 1
            : 1;

        $validated['customer_code'] =
            'CUS-' . str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );


        // Normalize WhatsApp
        $validated['whatsapp'] =
            $this->normalizeWhatsapp(
                $validated['whatsapp']
            );


        $customer = Customer::create($validated);


        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil ditambahkan.',
            'data' => $customer,
        ], 201);
    }


    public function update(
        Request $request,
        Customer $customer
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'whatsapp' => [
                'required',
                'string',
                'max:30',
            ],

            'guarantee_type' => [
                'nullable',
                Rule::in([
                    'ktp',
                    'sim',
                    'passport',
                    'other',
                ]),
            ],

            'guarantee_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);


        $validated['whatsapp'] =
            $this->normalizeWhatsapp(
                $validated['whatsapp']
            );


        $customer->update($validated);


        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil diperbarui.',
            'data' => $customer->fresh(),
        ]);
    }


    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil dihapus.',
        ]);
    }


    private function normalizeWhatsapp(string $number): string
    {
        $number = preg_replace(
            '/[^0-9+]/',
            '',
            $number
        );

        if (str_starts_with($number, '+62')) {

            return '62' . substr($number, 3);

        }

        if (str_starts_with($number, '62')) {

            return $number;

        }

        if (str_starts_with($number, '0')) {

            return '62' . substr($number, 1);

        }

        return $number;
    }
}