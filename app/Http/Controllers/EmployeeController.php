<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
{
    $query = User::query()
        ->latest();

    // SEARCH
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");

        });
    }

    // FILTER ROLE ← TARUH DI SINI
    if ($request->filled('role')) {

        $query->where(
            'role',
            $request->role
        );
    }

    $employees = $query
        ->paginate(10)
        ->withQueryString();

    if ($request->ajax()) {

        return response()->json([
            'data' => $employees->items(),
            'current_page' => $employees->currentPage(),
            'last_page' => $employees->lastPage(),
            'total' => $employees->total(),
        ]);
    }

    return view('employees.index', compact('employees'));
}


    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'kurir',
                    'staff',
                ]),
            ],

        ]);


        $employee = User::create([
            'name' => $validated['name'],

            'email' => $validated['email'],

            'password' => Hash::make(
                $validated['password']
            ),

            'role' => $validated['role'],
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil ditambahkan.',
            'data' => $employee,
        ], 201);
    }


    public function update(
        Request $request,
        User $employee
    ) {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($employee->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'kurir',
                    'staff',
                ]),
            ],

        ]);


        $employee->name =
            $validated['name'];

        $employee->email =
            $validated['email'];

        $employee->role =
            $validated['role'];


        if (!empty($validated['password'])) {

            $employee->password =
                Hash::make(
                    $validated['password']
                );

        }


        $employee->save();


        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil diperbarui.',
            'data' => $employee->fresh(),
        ]);
    }


    public function destroy(User $employee)
    {
        // Jangan izinkan admin menghapus akun
        // yang sedang digunakan untuk login.
        if ($employee->id === auth()->id()) {

            return response()->json([
                'success' => false,
                'message' => 'Akun yang sedang digunakan tidak dapat dihapus.',
            ], 422);

        }


        // Jangan sampai admin terakhir terhapus.
        if ($employee->role === 'admin') {

            $adminCount = User::where(
                'role',
                'admin'
            )->count();

            if ($adminCount <= 1) {

                return response()->json([
                    'success' => false,
                    'message' => 'Admin terakhir tidak boleh dihapus.',
                ], 422);

            }
        }


        $employee->delete();


        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil dihapus.',
        ]);
    }
}