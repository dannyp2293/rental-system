<x-app-layout>

    <div class="px-6 py-6">

        {{-- ===================================================== --}}
        {{-- ACTION BAR --}}
        {{-- ===================================================== --}}

        <div class="flex items-center justify-between mb-6">

            <div>

                <h1 class="text-2xl font-bold text-gray-900">
                    Invoice Rental
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Detail transaksi rental dan pembayaran.
                </p>

            </div>


            <div class="flex items-center gap-2">

                <button
                    type="button"
                    onclick="window.print()"
                    class="px-4 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700"
                >
                    🖨 Cetak Invoice
                </button>

                <button
                    type="button"
                    onclick="history.back()"
                    class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50"
                >
                    Kembali
                </button>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INVOICE --}}
        {{-- ===================================================== --}}

        <div
            id="invoice"
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
        >

            {{-- HEADER INVOICE --}}

            <div class="px-8 py-7 border-b border-gray-200">

                <div class="flex items-start justify-between gap-6">

                    <div>

                        <div class="flex items-center gap-3">

                            <div
                                class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xl font-bold"
                            >
                                R
                            </div>

                            <div>

                                <div class="text-xl font-bold text-gray-900">
                                    RENTAL
                                </div>

                                <div class="text-sm text-gray-500">
                                    MANAGEMENT
                                </div>

                            </div>

                        </div>

                        <div class="mt-5">

                            <h2 class="text-2xl font-bold text-gray-900">
                                INVOICE
                            </h2>

                            <p class="text-sm text-gray-500 mt-1">
                                Bukti transaksi rental
                            </p>

                        </div>

                    </div>


                    <div class="text-right">

                        <div class="text-sm text-gray-500">
                            No. Invoice
                        </div>

                        <div class="mt-1 text-lg font-bold text-gray-900">
                            INV-{{ $rental->rental_code }}
                        </div>

                        <div class="mt-2 text-sm text-gray-500">
                            Tanggal
                        </div>

                        <div class="text-sm font-medium text-gray-900">

                            {{ $rental->rental_start?->format('d/m/Y H:i') ?? '-' }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- CUSTOMER + RENTAL INFO --}}
            {{-- ================================================= --}}

            <div class="px-8 py-6 border-b border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    {{-- CUSTOMER --}}

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                            Pelanggan
                        </div>

                        <div class="mt-2 text-lg font-semibold text-gray-900">
                            {{ $rental->customer?->full_name ?? $rental->customer?->name ?? '-' }}
                        </div>

                        <div class="mt-1 text-sm text-gray-500">
                            ID Customer:
                            {{ $rental->customer?->customer_code ?? '-' }}
                        </div>

                        @if($rental->customer?->whatsapp)

                            <div class="mt-1 text-sm text-gray-500">
                                WhatsApp:
                                {{ $rental->customer->whatsapp }}
                            </div>

                        @endif

                        @if($rental->customer?->address)

                            <div class="mt-1 text-sm text-gray-500">
                                {{ $rental->customer->address }}
                            </div>

                        @endif

                    </div>


                    {{-- RENTAL --}}

                    <div>

                        <div class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                            Informasi Rental
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-y-2 text-sm">

                            <div class="text-gray-500">
                                Kode Rental
                            </div>

                            <div class="font-semibold text-gray-900 text-right">
                                {{ $rental->rental_code }}
                            </div>


                            <div class="text-gray-500">
                                Mulai Rental
                            </div>

                            <div class="text-gray-900 text-right">
                                {{ $rental->rental_start?->format('d/m/Y H:i') ?? '-' }}
                            </div>


                            <div class="text-gray-500">
                                Selesai Rental
                            </div>

                            <div class="text-gray-900 text-right">
                                {{ $rental->rental_end?->format('d/m/Y H:i') ?? '-' }}
                            </div>


                            @if($rental->returned_at)

                                <div class="text-gray-500">
                                    Dikembalikan
                                </div>

                                <div class="text-gray-900 text-right">
                                    {{ $rental->returned_at->format('d/m/Y H:i') }}
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ITEMS --}}
            {{-- ================================================= --}}

            <div class="px-8 py-6">

                <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">
                    Detail Produk
                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">

                        <thead>

                            <tr class="border-b border-gray-200">

                                <th class="py-3 text-left font-semibold text-gray-700">
                                    No
                                </th>

                                <th class="py-3 text-left font-semibold text-gray-700">
                                    Produk
                                </th>

                                <th class="py-3 text-center font-semibold text-gray-700">
                                    Qty
                                </th>

                                <th class="py-3 text-right font-semibold text-gray-700">
                                    Harga
                                </th>

                                <th class="py-3 text-right font-semibold text-gray-700">
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($rental->items as $index => $item)

                                @php

                                    $qty = (float) ($item->quantity ?? 1);

                                    $price = (float) (
                                        $item->price
                                        ?? $item->unit_price
                                        ?? 0
                                    );

                                    $itemTotal = $qty * $price;

                                @endphp

                                <tr class="border-b border-gray-100">

                                    <td class="py-3">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="py-3">

                                        <div class="font-medium text-gray-900">
                                            {{ $item->product?->name ?? '-' }}
                                        </div>

                                    </td>

                                    <td class="py-3 text-center">
                                        {{ $qty }}
                                    </td>

                                    <td class="py-3 text-right">
                                        Rp {{ number_format($price, 0, ',', '.') }}
                                    </td>

                                    <td class="py-3 text-right font-medium">
                                        Rp {{ number_format($itemTotal, 0, ',', '.') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="py-8 text-center text-gray-500"
                                    >
                                        Tidak ada detail produk.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- TOTAL --}}
            {{-- ================================================= --}}

            <div class="px-8 pb-8">

                <div class="flex justify-end">

                    <div class="w-full md:w-96">

                        <div class="border-t border-gray-200 pt-4 space-y-3">

                            <div class="flex justify-between text-sm">

                                <span class="text-gray-500">
                                    Subtotal
                                </span>

                                <span class="font-medium text-gray-900">
                                    Rp {{ number_format($rental->subtotal ?? 0, 0, ',', '.') }}
                                </span>

                            </div>


                            <div class="flex justify-between text-sm">

                                <span class="text-gray-500">
                                    Diskon
                                </span>

                                <span class="font-medium text-gray-900">
                                    Rp {{ number_format($rental->discount ?? 0, 0, ',', '.') }}
                                </span>

                            </div>


                            <div class="flex justify-between text-sm">

                                <span class="text-gray-500">
                                    Deposit
                                </span>

                                <span class="font-medium text-gray-900">
                                    Rp {{ number_format($rental->deposit ?? 0, 0, ',', '.') }}
                                </span>

                            </div>


                            <div class="flex justify-between pt-3 border-t border-gray-200">

                                <span class="text-base font-bold text-gray-900">
                                    TOTAL
                                </span>

                                <span class="text-xl font-bold text-gray-900">
                                    Rp {{ number_format($rental->total ?? 0, 0, ',', '.') }}
                                </span>

                            </div>


                            <div class="flex justify-between text-sm pt-2">

                                <span class="text-gray-500">
                                    Sudah Dibayar
                                </span>

                                <span class="font-semibold text-green-600">
                                    Rp {{ number_format($paid, 0, ',', '.') }}
                                </span>

                            </div>


                            <div class="flex justify-between text-sm">

                                <span class="text-gray-500">
                                    Sisa Tagihan
                                </span>

                                <span class="font-semibold text-red-600">
                                    Rp {{ number_format($remaining, 0, ',', '.') }}
                                </span>

                            </div>


                            <div class="pt-4">

                                @if($paymentStatus === 'PAID')

                                    <div class="flex justify-between items-center px-4 py-3 rounded-lg bg-green-50 border border-green-200">

                                        <span class="text-sm font-semibold text-green-700">
                                            Status Pembayaran
                                        </span>

                                        <span class="px-3 py-1 rounded-full bg-green-600 text-white text-xs font-bold">
                                            LUNAS
                                        </span>

                                    </div>

                                @elseif($paymentStatus === 'PARTIAL')

                                    <div class="flex justify-between items-center px-4 py-3 rounded-lg bg-yellow-50 border border-yellow-200">

                                        <span class="text-sm font-semibold text-yellow-700">
                                            Status Pembayaran
                                        </span>

                                        <span class="px-3 py-1 rounded-full bg-yellow-500 text-white text-xs font-bold">
                                            SEBAGIAN
                                        </span>

                                    </div>

                                @else

                                    <div class="flex justify-between items-center px-4 py-3 rounded-lg bg-red-50 border border-red-200">

                                        <span class="text-sm font-semibold text-red-700">
                                            Status Pembayaran
                                        </span>

                                        <span class="px-3 py-1 rounded-full bg-red-600 text-white text-xs font-bold">
                                            BELUM BAYAR
                                        </span>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- PAYMENT HISTORY --}}
            {{-- ================================================= --}}

            @if($rental->payments->count())

                <div class="px-8 py-6 border-t border-gray-200">

                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">
                        Riwayat Pembayaran
                    </div>

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead>

                                <tr class="border-b border-gray-200">

                                    <th class="py-3 text-left font-semibold text-gray-700">
                                        Kode
                                    </th>

                                    <th class="py-3 text-left font-semibold text-gray-700">
                                        Tanggal
                                    </th>

                                    <th class="py-3 text-left font-semibold text-gray-700">
                                        Metode
                                    </th>

                                    <th class="py-3 text-right font-semibold text-gray-700">
                                        Jumlah
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($rental->payments as $payment)

                                    <tr class="border-b border-gray-100">

                                        <td class="py-3 font-medium">
                                            {{ $payment->payment_code }}
                                        </td>

                                        <td class="py-3">
                                            {{ $payment->paid_at?->format('d/m/Y H:i') ?? '-' }}
                                        </td>

                                        <td class="py-3">
                                            {{ strtoupper(str_replace('_', ' ', $payment->method)) }}
                                        </td>

                                        <td class="py-3 text-right font-medium text-green-600">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- FOOTER --}}
            {{-- ================================================= --}}

            <div class="px-8 py-5 bg-gray-50 border-t border-gray-200">

                <div class="flex items-center justify-between text-xs text-gray-500">

                    <div>
                        Terima kasih telah menggunakan Rental Management.
                    </div>

                    <div>
                        Dicetak {{ now()->format('d/m/Y H:i') }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PRINT STYLE --}}
    {{-- ========================================================= --}}

    <style>

        @media print {

            @page {
                size: A4;
                margin: 12mm;
            }

            body {
                background: white !important;
            }

            body * {
                visibility: hidden;
            }

            #invoice,
            #invoice * {
                visibility: visible;
            }

            #invoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
            }

            .no-print {
                display: none !important;
            }

        }

    </style>

</x-app-layout>