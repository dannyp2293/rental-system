<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        $totalEmployees = User::where('role', 'employee')->count();

        return view('dashboard.index', compact(
            'totalCustomers',
            'totalProducts',
            'totalCategories',
            'totalEmployees'
        ));
    }
}