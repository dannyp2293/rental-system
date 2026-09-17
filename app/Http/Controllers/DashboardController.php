<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Rental;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Statistik Master Data
        |--------------------------------------------------------------------------
        */

        $totalCustomers = Customer::count();

        $totalProducts = Product::count();

        $totalCategories = Category::count();

        // Admin tidak dihitung sebagai karyawan.
        // Karyawan terdiri dari Staff + Kurir.
        $totalEmployees = User::whereIn('role', [
            'staff',
            'kurir',
        ])->count();


        /*
        |--------------------------------------------------------------------------
        | Statistik Rental
        |--------------------------------------------------------------------------
        */

        $totalRentals = Rental::count();

        /*
        |--------------------------------------------------------------------------
        | Rental Terbaru
        |--------------------------------------------------------------------------
        */

        $latestRentals = Rental::with('customer')
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Total Nilai Rental
        |--------------------------------------------------------------------------
        */

        $totalRentalValue = Rental::sum('total');


        /*
        |--------------------------------------------------------------------------
        | Kirim Data ke View
        |--------------------------------------------------------------------------
        */

        return view('dashboard.index', compact(
            'totalCustomers',
            'totalProducts',
            'totalCategories',
            'totalEmployees',
            'totalRentals',
            'latestRentals',
            'totalRentalValue'
        ));
    }
}