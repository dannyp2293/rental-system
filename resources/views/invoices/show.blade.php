<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Invoice {{ $rental->rental_code }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {

            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .invoice-container {
                max-width: 100% !important;
                padding: 0 !important;
            }

            .invoice-card {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="invoice-container mx-auto max-w-4xl px-6 py-10">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="mb-6 flex items-start justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-600 text-lg font-bold text-white">
                        R
                    </div>

                    <div>

                        <h1 class="text-xl font-bold">
                            RENTAL
                        </h1>

                        <p class="text-xs text-gray-500">
                            MANAGEMENT
                        </p>

                    </div>

                </div>

                <div class="mt-6">

                    <h2 class="text-3xl font-bold">
                        INVOICE
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $rental->rental_code }}
                    </p>

                </div>

            </div>


            {{-- PAYMENT STATUS --}}
            <div class="text-right">

                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Status Pembayaran
                </p>


                @if($paymentStatus === 'PAID')

                    <span class="inline-flex rounded-full bg-green-100 px-4 py-2 text-xs font-bold text-green-700">
                        LUNAS
                    </span>

                @elseif($paymentStatus === 'PARTIAL')

                    <span class="inline-flex rounded-full bg-yellow-100 px-4 py-2 text-xs font-bold text-yellow-700">
                        SEBAGIAN
                    </span>

                @else

                    <span class="inline-flex rounded-full bg-red-100 px-4 py-2 text-xs font-bold text-red-700">
                        BELUM BAYAR
                    </span>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- CUSTOMER + RENTAL INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="invoice-card mb-6 grid gap-6 rounded-xl bg-white p-6 shadow-sm md:grid-cols-2">

            {{-- CUSTOMER --}}
            <div>

                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Customer
                </p>

                <p class="text-lg font-bold text-gray-900">
                    {{ $rental->customer->name ?? '-' }}
                </p>

                @if($rental->customer?->code)

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $rental->customer->code }}
                    </p>

                @endif

                @if($rental->customer?->phone)

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $rental->customer->phone }}
                    </p>

                @endif

            </div>


            {{-- PERIODE RENTAL --}}
            <div class="md:text-right">

                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Periode Rental
                </p>

                <p class="text-sm font-semibold text-gray-900">
                    {{ $rental->rental_start?->format('d/m/Y H:i') }}
                </p>

                <p class="my-1 text-xs text-gray-400">
                    sampai
                </p>

                <p class="text-sm font-semibold text-gray-900">
                    {{ $rental->rental_end?->format('d/m/Y H:i') }}
                </p>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- DETAIL RENTAL --}}
        {{-- ========================================================= --}}

        <div class="invoice-card overflow-hidden rounded-xl bg-white shadow-sm">

            <div class="border-b px-6 py-5">

                <h3 class="font-bold text-gray-900">
                    Detail Rental
                </h3>

                <p class="mt-1 text-xs text-gray-500">
                    Daftar produk yang disewa.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Produk
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Qty
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Durasi
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Harga
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Subtotal
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($rental->items as $item)

                            <tr>

                                {{-- PRODUK --}}
                                <td class="px-6 py-4">

                                    <div class="font-semibold text-gray-900">
                                        {{ $item->product->name ?? '-' }}
                                    </div>

                                    @if($item->product?->code)

                                        <div class="mt-1 text-xs text-gray-400">
                                            {{ $item->product->code }}
                                        </div>

                                    @endif

                                </td>


                                {{-- QTY --}}
                                <td class="px-6 py-4 text-center text-sm">

                                    {{ $item->quantity }}

                                    <span class="text-xs text-gray-400">
                                        unit
                                    </span>

                                </td>


                                {{-- DURASI --}}
                                <td class="px-6 py-4 text-center text-sm">

                                    {{ $item->duration }}

                                    @if($item->pricing_type === 'per_hour')

                                        <span class="text-xs text-gray-400">
                                            jam
                                        </span>

                                    @else

                                        <span class="text-xs text-gray-400">
                                            hari
                                        </span>

                                    @endif

                                </td>


                                {{-- HARGA --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">

                                    Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}

                                    <div class="mt-1 text-xs text-gray-400">

                                        @if($item->pricing_type === 'per_hour')
                                            / jam
                                        @else
                                            / hari
                                        @endif

                                    </div>

                                </td>


                                {{-- SUBTOTAL --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold">

                                    Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="px-6 py-10 text-center text-sm text-gray-500"
                                >
                                    Tidak ada detail produk rental.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TOTAL --}}
        {{-- ========================================================= --}}

        <div class="mt-6 flex justify-end">

            <div class="invoice-card w-full rounded-xl bg-white p-6 shadow-sm md:max-w-md">

                {{-- SUBTOTAL --}}
                <div class="flex items-center justify-between text-sm">

                    <span class="text-gray-500">
                        Subtotal
                    </span>

                    <span class="font-medium text-gray-900">
                        Rp {{ number_format((float) $rental->subtotal, 0, ',', '.') }}
                    </span>

                </div>


                {{-- DISKON --}}
                <div class="mt-3 flex items-center justify-between text-sm">

                    <span class="text-gray-500">
                        Diskon
                    </span>

                    <span class="font-medium text-gray-900">
                        Rp {{ number_format((float) $rental->discount, 0, ',', '.') }}
                    </span>

                </div>


                {{-- TOTAL --}}
                <div class="mt-4 border-t pt-4">

                    <div class="flex items-center justify-between">

                        <span class="text-base font-bold text-gray-900">
                            TOTAL
                        </span>

                        <span class="text-2xl font-bold text-indigo-600">
                            Rp {{ number_format((float) $rental->total, 0, ',', '.') }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- PAYMENT --}}
        {{-- ========================================================= --}}

        <div class="mt-6 invoice-card rounded-xl bg-white p-6 shadow-sm">

            <div class="mb-5">

                <h3 class="font-bold text-gray-900">
                    Pembayaran
                </h3>

                <p class="mt-1 text-xs text-gray-500">
                    Ringkasan pembayaran rental.
                </p>

            </div>


            {{-- TOTAL --}}
            <div class="flex items-center justify-between text-sm">

                <span class="text-gray-500">
                    Total Rental
                </span>

                <span class="font-semibold">
                    Rp {{ number_format((float) $rental->total, 0, ',', '.') }}
                </span>

            </div>


            {{-- SUDAH BAYAR --}}
            <div class="mt-3 flex items-center justify-between text-sm">

                <span class="text-gray-500">
                    Sudah Dibayar
                </span>

                <span class="font-semibold text-green-600">
                    Rp {{ number_format((float) $paid, 0, ',', '.') }}
                </span>

            </div>


            {{-- SISA --}}
            <div class="mt-3 flex items-center justify-between border-t pt-4">

                <span class="font-semibold text-gray-900">
                    Sisa Tagihan
                </span>

                <span class="text-lg font-bold text-red-600">
                    Rp {{ number_format((float) $remaining, 0, ',', '.') }}
                </span>

            </div>


            {{-- PAYMENT HISTORY --}}
            @if($rental->payments->count() > 0)

                <div class="mt-6 border-t pt-5">

                    <h4 class="mb-3 text-sm font-semibold text-gray-700">
                        Riwayat Pembayaran
                    </h4>


                    <div class="divide-y divide-gray-100">

                        @foreach($rental->payments as $payment)

                            <div class="flex items-center justify-between py-3">

                                <div>

                                    <p class="text-sm font-medium text-gray-900">

                                        {{ $payment->payment_date
                                            ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i')
                                            : '-' }}

                                    </p>

                                    @if($payment->method)

                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ ucfirst($payment->method) }}
                                        </p>

                                    @endif

                                </div>


                                <div class="text-right">

                                    <p class="text-sm font-semibold text-green-600">
                                        Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                                    </p>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- DEPOSIT --}}
        {{-- ========================================================= --}}

        @if((float) $rental->deposit > 0)

            <div class="mt-6 invoice-card rounded-xl bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <h3 class="font-semibold text-gray-900">
                            Deposit / Jaminan
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Deposit yang diberikan untuk transaksi ini.
                        </p>

                    </div>

                    <span class="text-lg font-bold text-gray-900">
                        Rp {{ number_format((float) $rental->deposit, 0, ',', '.') }}
                    </span>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- CATATAN --}}
        {{-- ========================================================= --}}

        @if($rental->notes)

            <div class="mt-6 invoice-card rounded-xl bg-white p-6 shadow-sm">

                <h3 class="font-semibold text-gray-900">
                    Catatan
                </h3>

                <p class="mt-2 whitespace-pre-line text-sm text-gray-600">
                    {{ $rental->notes }}
                </p>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- FOOTER / ACTION --}}
        {{-- ========================================================= --}}

        <div class="no-print mt-6 flex justify-end gap-3">

            <button
                type="button"
                onclick="window.history.back()"
                class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
            >
                Kembali
            </button>


            <button
                type="button"
                onclick="window.print()"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
            >
                Cetak Invoice
            </button>

        </div>


        {{-- ========================================================= --}}
        {{-- FOOTER --}}
        {{-- ========================================================= --}}

        <div class="mt-8 text-center">

            <p class="text-xs text-gray-400">
                Terima kasih telah menggunakan layanan rental kami.
            </p>

        </div>

    </div>

</body>

</html>