<div class="overflow-x-auto">

    <table class="min-w-full">

        <thead class="bg-gray-50">

            <tr>

                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                    #
                </th>

                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Produk
                </th>

                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Kategori
                </th>

                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Harga / Hari
                </th>

                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Stok
                </th>

                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Status
                </th>

                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody class="divide-y divide-gray-100 bg-white">

            @forelse($products as $product)
                <tr id="product-row-{{ $product->id }}" class="transition hover:bg-gray-50">

                    {{-- Number --}}
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                        {{ $products->firstItem() + $loop->index }}
                    </td>


                    {{-- Product --}}
                    <td class="px-6 py-4">

                        <div class="flex items-center gap-3">

                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="h-11 w-11 rounded-lg object-cover">
                            @else
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-lg bg-gray-100 text-gray-400">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16l4-4 4 4 3-3 5 5M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>

                                </div>
                            @endif


                            <div>

                                <div class="font-semibold text-gray-900">
                                    {{ $product->name }}
                                </div>

                                <div class="text-xs text-gray-400">
                                    {{ $product->code }}
                                </div>

                            </div>

                        </div>

                    </td>


                    {{-- Category --}}
                    <td class="px-6 py-4 text-sm text-gray-600">

                        {{ $product->category?->name ?? '-' }}

                    </td>


                    {{-- Price --}}
                    <td class="whitespace-nowrap px-6 py-4">

                        <div class="text-sm font-semibold text-gray-900">
                            Rp {{ number_format($product->price_per_day, 0, ',', '.') }}
                        </div>

                        <div class="text-xs text-gray-400">
                            / hari
                        </div>

                    </td>


                    {{-- Stock --}}
                    {{-- Stock --}}
                    <td class="px-6 py-4">

                        @php
                            $totalStock = (int) $product->stock;

                            $rentedStock = (int) ($product->rented_stock ?? 0);

                            // Jangan sampai angka tersedia minus
                            $rentedStock = min($rentedStock, $totalStock);

                            $availableStock = max($totalStock - $rentedStock, 0);
                        @endphp

                        <div class="space-y-1 text-sm">

                            {{-- Total Stock --}}
                            <div>
                                <span class="font-semibold text-gray-800">
                                    {{ number_format($totalStock) }}
                                </span>
                                <span class="text-gray-500">
                                    unit total
                                </span>
                            </div>

                            {{-- Available --}}
                            <div class="text-green-600">
                                <span class="font-medium">
                                    Tersedia:
                                </span>

                                <span class="font-semibold">
                                    {{ number_format($availableStock) }}
                                </span>
                                unit
                            </div>

                            {{-- Rented --}}
                            <div class="text-blue-600">
                                <span class="font-medium">
                                    Dirental:
                                </span>

                                <span class="font-semibold">
                                    {{ number_format($rentedStock) }}
                                </span>
                                unit
                            </div>

                        </div>

                    </td>


                    {{-- Status --}}
                    <td class="whitespace-nowrap px-6 py-4">

                        @if ($product->status === 'available')
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                Tersedia
                            </span>
                        @elseif($product->status === 'rented')
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                Disewa
                            </span>
                        @else
                            <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700">
                                Maintenance
                            </span>
                        @endif

                    </td>


                    {{-- Action --}}
                    <td class="whitespace-nowrap px-6 py-4 text-right">

                        <button type="button" onclick='openEditModal(@json($product))'
                            class="mr-3 text-sm font-semibold text-blue-600 hover:text-blue-800">
                            Edit
                        </button>



                        <button type="button" onclick="deleteProduct({{ $product->id }})"
                            class="text-sm font-semibold text-red-600 hover:text-red-800">
                            Hapus
                        </button>



                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="px-6 py-16 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 13V7a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 7v6a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4a2 2 0 001-1.73V7" />
                            </svg>

                        </div>

                        <h4 class="mt-4 font-semibold text-gray-700">
                            Belum ada produk
                        </h4>

                        <p class="mt-1 text-sm text-gray-500">
                            Tambahkan produk rental pertama kamu.
                        </p>

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

</div>
