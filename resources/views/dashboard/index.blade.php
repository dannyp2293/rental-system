<x-app-layout>

    {{-- Header --}}
    <x-slot name="header">

        <div>
            <h2 class="text-xl font-bold text-gray-900">
                Dashboard
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Ringkasan aktivitas usaha rental kamu.
            </p>
        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- Welcome --}}
            <div class="mb-8">

                <h1 class="text-2xl font-bold text-gray-900">
                    Selamat datang, {{ auth()->user()->name }} 👋
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Berikut ringkasan sistem rental kamu hari ini.
                </p>

            </div>


            {{-- Statistics --}}
            {{-- <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4"> --}}
                <div class="dashboard-stat-grid">


                {{-- Customer --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Total Customer
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-900">
                                {{ number_format($totalCustomers) }}
                            </p>

                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19a3 3 0 10-6 0m9-9a3 3 0 11-6 0 3 3 0 016 0zM3 20a6 6 0 0112 0M16 7a3 3 0 110 6"
                                />
                            </svg>

                        </div>

                    </div>

                    <div class="mt-4">

                        <a
                            href="#"
                            class="text-sm font-semibold text-blue-600 hover:text-blue-800"
                        >
                            Kelola customer →
                        </a>

                    </div>

                </div>


                {{-- Product --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Total Produk
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-900">
                                {{ number_format($totalProducts) }}
                            </p>

                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M20 13V7a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 7v6a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0020 13z"
                                />
                            </svg>

                        </div>

                    </div>

                    <div class="mt-4">

                        <a
                            href="#"
                            class="text-sm font-semibold text-purple-600 hover:text-purple-800"
                        >
                            Kelola produk →
                        </a>

                    </div>

                </div>


                {{-- Category --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Kategori
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-900">
                                {{ number_format($totalCategories) }}
                            </p>

                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7 7h.01M3 11l8.59 8.59a2 2 0 002.82 0l6.59-6.59a2 2 0 000-2.82L12.41 3H5a2 2 0 00-2 2v6z"
                                />
                            </svg>

                        </div>

                    </div>

                    <div class="mt-4">

                        <a
                            href="{{ route('categories.index') }}"
                            class="text-sm font-semibold text-orange-600 hover:text-orange-800"
                        >
                            Kelola kategori →
                        </a>

                    </div>

                </div>


                {{-- Employees --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-100">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-gray-500">
                                Karyawan
                            </p>

                            <p class="mt-2 text-3xl font-bold text-gray-900">
                                {{ number_format($totalEmployees) }}
                            </p>

                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6-4a3 3 0 10-6 0"
                                />
                            </svg>

                        </div>

                    </div>

                    <div class="mt-4">

                        <a
                            href="#"
                            class="text-sm font-semibold text-green-600 hover:text-green-800"
                        >
                            Kelola karyawan →
                        </a>

                    </div>

                </div>

            </div>


            {{-- Main Content --}}
           <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">


                {{-- Rental --}}
                <div class="lg:col-span-2 rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

                    <div class="border-b border-gray-100 px-6 py-5">

                        <div class="flex items-center justify-between">

                            <div>

                                <h3 class="font-bold text-gray-900">
                                    Rental Terbaru
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Transaksi rental terbaru akan tampil di sini.
                                </p>

                            </div>

                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-500">
                                Segera hadir
                            </span>

                        </div>

                    </div>

                    <div class="flex min-h-[280px] items-center justify-center px-6">

                        <div class="text-center">

                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 14l2 2 4-4m5-3a7 7 0 11-14 0 7 7 0 0114 0z"
                                    />
                                </svg>

                            </div>

                            <h4 class="mt-4 font-semibold text-gray-700">
                                Belum ada transaksi rental
                            </h4>

                            <p class="mt-1 text-sm text-gray-500">
                                Data akan otomatis muncul setelah transaksi dibuat.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Quick Action --}}
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

                    <div class="border-b border-gray-100 px-6 py-5">

                        <h3 class="font-bold text-gray-900">
                            Aksi Cepat
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Akses menu yang sering digunakan.
                        </p>

                    </div>


                    <div class="space-y-3 p-6">

                        <button
                            type="button"
                            class="flex w-full items-center gap-3 rounded-xl border border-gray-200 p-4 text-left transition hover:border-gray-300 hover:bg-gray-50"
                        >

                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-900 text-white">
                                +
                            </div>

                            <div>

                                <p class="text-sm font-semibold text-gray-900">
                                    Buat Rental
                                </p>

                                <p class="text-xs text-gray-500">
                                    Buat transaksi baru
                                </p>

                            </div>

                        </button>


                        <button
                            type="button"
                            class="flex w-full items-center gap-3 rounded-xl border border-gray-200 p-4 text-left transition hover:border-gray-300 hover:bg-gray-50"
                        >

                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-700">
                                +
                            </div>

                            <div>

                                <p class="text-sm font-semibold text-gray-900">
                                    Tambah Customer
                                </p>

                                <p class="text-xs text-gray-500">
                                    Daftarkan customer baru
                                </p>

                            </div>

                        </button>


                        <a
                            href="{{ route('categories.index') }}"
                            class="flex w-full items-center gap-3 rounded-xl border border-gray-200 p-4 text-left transition hover:border-gray-300 hover:bg-gray-50"
                        >

                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-700">
                                +
                            </div>

                            <div>

                                <p class="text-sm font-semibold text-gray-900">
                                    Kelola Kategori
                                </p>

                                <p class="text-xs text-gray-500">
                                    Atur kategori produk
                                </p>

                            </div>

                        </a>

                    </div>

                </div>

            </div>


            {{-- Coming Soon --}}
            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-3">

                <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6">

                    <p class="text-sm font-semibold text-gray-700">
                        💰 Pemasukan
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        Statistik pemasukan akan aktif setelah modul transaksi selesai.
                    </p>

                </div>


                <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6">

                    <p class="text-sm font-semibold text-gray-700">
                        📦 Rental Aktif
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        Jumlah rental aktif akan tampil setelah modul rental selesai.
                    </p>

                </div>


                <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6">

                    <p class="text-sm font-semibold text-gray-700">
                        🎁 Bonus Karyawan
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        Perhitungan bonus akan terhubung dengan transaksi rental.
                    </p>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>