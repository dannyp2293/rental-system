<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rental Management') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        aside a {
            text-decoration: none !important;
        }

        aside a:hover {
            text-decoration: none !important;
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen">

        {{-- ========================================================= --}}
        {{-- MOBILE OVERLAY --}}
        {{-- ========================================================= --}}

        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden" style="display: none;"></div>


        {{-- ========================================================= --}}
        {{-- SIDEBAR --}}
        {{-- ========================================================= --}}

        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transform transition-transform duration-300 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- BRAND --}}
            <div class="flex h-16 items-center px-6 border-b border-slate-700">

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 font-bold">
                        R
                    </div>

                    <div>
                        <div class="text-sm font-bold tracking-wide">
                            RENTAL
                        </div>

                        <div class="text-xs text-slate-400">
                            MANAGEMENT
                        </div>
                    </div>

                </a>

            </div>


            {{-- NAVIGATION --}}
            <nav class="mt-5 px-3 space-y-6 overflow-y-auto h-[calc(100vh-9rem)]">

                {{-- DASHBOARD --}}

                <div>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('dashboard')
                        ? 'bg-blue-600 text-white'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />
                        </svg>

                        Dashboard

                    </a>

                </div>


                {{-- ================================================= --}}
                {{-- MASTER DATA --}}
                {{-- ================================================= --}}

                <div>

                    <div class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Master Data
                    </div>


                    <div class="space-y-1">

                        {{-- CATEGORY --}}

                        {{-- CATEGORY --}}
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('categories.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                            {{ request()->routeIs('categories.*')
                                ? 'bg-slate-800 text-white'
                                : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <span class="w-5 text-center">▦</span>
                                Category
                            </a>
                        @endif


                        {{-- PRODUCT --}}

                        {{-- PRODUCT --}}
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('products.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                                {{ request()->routeIs('products.*')
                                    ? 'bg-slate-800 text-white'
                                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <span class="w-5 text-center">▣</span>
                                Product
                            </a>
                        @endif

                        {{-- CUSTOMER --}}

                        <a href="{{ route('customers.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('customers.*')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                            <span class="w-5 text-center">♙</span>

                            Customer

                        </a>


                        {{-- EMPLOYEE --}}
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('employees.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                            {{ request()->routeIs('employees.*')
                                ? 'bg-slate-800 text-white'
                                : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <span class="w-5 text-center">♟</span>
                                Employee
                            </a>
                        @endif

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TRANSAKSI --}}
                {{-- ================================================= --}}

                <div>

                    <div class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Transaksi
                    </div>


                    <div class="space-y-1">

                        {{-- RENTAL --}}

                        <a href="{{ route('rentals.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('rentals.*')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                            <span class="w-5 text-center">↔</span>

                            Rental

                        </a>


                        {{-- PENGEMBALIAN --}}

                        <a href="{{ route('rental-returns.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('rental-returns.*')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                            <span class="w-5 text-center">↩</span>

                            Pengembalian

                        </a>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- KEUANGAN --}}
                {{-- ================================================= --}}

                <div>

                    <div class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Keuangan
                    </div>


                    <div class="space-y-1">

                        {{-- PAYMENT --}}

                        <a href="{{ route('payments.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                        {{ request()->routeIs('payments.*')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                            <span class="w-5 text-center">Rp</span>

                            Payment

                        </a>

                    </div>

                    @if (auth()->user()->role === 'admin')

                        {{-- ================================================= --}}
                        {{-- LAPORAN --}}
                        {{-- ================================================= --}}

                        <div>

                            <div class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Laporan
                            </div>

                            <div class="space-y-1">

                                <a href="{{ route('reports.transactions') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                                {{ request()->routeIs('reports.*')
                                    ? 'bg-slate-800 text-white'
                                    : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                                    <span class="w-5 text-center">▤</span>

                                    Laporan Transaksi

                                </a>

                            </div>

                            <a href="{{ route('reports.income') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                                    {{ request()->routeIs('reports.income')
                                        ? 'bg-slate-800 text-white'
                                        : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">

                                <span class="w-5 text-center">💰</span>

                                Laporan Pendapatan

                            </a>

                            {{-- BONUS KARYAWAN --}}
                            <a href="{{ route('bonuses.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                            {{ request()->routeIs('bonuses.index')
                                ? 'bg-slate-800 text-white'
                                : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <span class="w-5 text-center">🎁</span>
                                Bonus Karyawan
                            </a>

                            {{-- REKAP BONUS --}}
                            <a href="{{ route('bonuses.report') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                            {{ request()->routeIs('bonuses.report')
                                ? 'bg-slate-800 text-white'
                                : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                <span class="w-5 text-center">📊</span>
                                Rekap Bonus
                            </a>

                            @if (auth()->user()->role === 'admin')
                                <div class="mt-5">
                                    <div class="px-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                        Sistem
                                    </div>

                                    <a href="{{ route('backups.index') }}"
                                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 transition">
                                        <span>💾</span>
                                        <span>Backup Data</span>
                                    </a>
                                </div>
                            @endif

                        </div>

                    @endif

                </div>

            </nav>


            {{-- USER / LOGOUT --}}

            <div class="absolute bottom-0 left-0 right-0 border-t border-slate-700 bg-slate-900 p-3">

                <div class="flex items-center justify-between gap-3">

                    <a href="{{ route('profile.edit') }}" class="min-w-0 flex-1">

                        <div class="truncate text-sm font-medium text-white">
                            {{ Auth::user()->name }}
                        </div>

                        <div class="truncate text-xs text-slate-400">
                            {{ Auth::user()->email }}
                        </div>

                    </a>


                    <form method="POST" action="{{ route('logout') }}">

                        @csrf

                        <button type="submit" title="Logout"
                            class="rounded-lg p-2 text-slate-400 hover:bg-slate-800 hover:text-white">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />

                            </svg>

                        </button>

                    </form>

                </div>

            </div>

        </aside>


        {{-- ========================================================= --}}
        {{-- MAIN --}}
        {{-- ========================================================= --}}

        <div class="lg:pl-64">

            {{-- TOPBAR --}}

            <header class="sticky top-0 z-30 h-16 bg-white border-b border-gray-200">

                <div class="h-full px-4 sm:px-6 lg:px-8 flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        {{-- MOBILE MENU --}}

                        <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden inline-flex items-center justify-center rounded-lg p-2 text-gray-600 hover:bg-gray-100">

                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />

                            </svg>

                        </button>


                        {{-- PAGE HEADER --}}

                        @isset($header)
                            <div class="text-lg font-semibold text-gray-800">

                                {{ $header }}

                            </div>
                        @else
                            <div class="text-lg font-semibold text-gray-800">

                                Rental Management

                            </div>
                        @endisset

                    </div>


                    {{-- USER --}}

                    <div class="hidden sm:flex items-center gap-3">

                        <div class="text-right">

                            <div class="text-sm font-medium text-gray-700">
                                {{ Auth::user()->name }}
                            </div>

                            <div class="text-xs text-gray-400">
                                {{ ucfirst(Auth::user()->role ?? 'User') }}
                            </div>

                        </div>

                    </div>

                </div>

            </header>


            {{-- PAGE CONTENT --}}

            <main class="min-h-[calc(100vh-4rem)]">

                {{ $slot }}

            </main>

        </div>

    </div>


    @stack('scripts')

</body>

</html>
