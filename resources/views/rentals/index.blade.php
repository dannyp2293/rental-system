<x-app-layout>

    <div class="max-w-7xl mx-auto py-6 px-4">

        {{-- =========================================================
        HEADER
    ========================================================== --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Rental
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Kelola transaksi penyewaan produk.
                </p>
            </div>

            <button type="button" onclick="openCreateModal()" style="background-color: #4f46e5; color: white;"
                class="hover:opacity-90 px-4 py-2 rounded-lg font-medium">
                + Tambah Rental
            </button>

        </div>


        {{-- =========================================================
        FILTER
    ========================================================== --}}
        <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Cari
                    </label>

                    <input type="text" id="search" placeholder="Kode rental / customer..."
                        class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>

                    <select id="statusFilter"
                        class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="returned">Returned</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>


                <div class="flex items-end">
                    <button type="button" onclick="loadRentals()"
                        class="w-full bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg">
                        Cari
                    </button>
                </div>

            </div>

        </div>


        {{-- =========================================================
        TABLE
    ========================================================== --}}
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Kode
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Customer
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Periode
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Total
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                Status
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody id="rentalTableBody" class="bg-white divide-y divide-gray-200">

                        <tr>

                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Memuat data...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PAGINATION --}}
        <div id="pagination" class="mt-4 flex justify-center"></div>

    </div>


    {{-- =========================================================
    MODAL RENTAL
========================================================== --}}

    <div id="rentalModal" class="fixed inset-0 z-50 hidden">

        <div class="absolute inset-0 bg-black/50" onclick="closeRentalModal()"></div>


        <div class="relative min-h-screen flex items-center justify-center p-4">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">

                {{-- MODAL HEADER --}}
                <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between z-10">

                    <div>

                        <h2 id="modalTitle" class="text-xl font-bold text-gray-800">
                            Tambah Rental
                        </h2>

                        <p class="text-sm text-gray-500">
                            Buat transaksi penyewaan baru.
                        </p>

                    </div>


                    <button type="button" onclick="closeRentalModal()"
                        class="text-gray-400 hover:text-gray-700 text-2xl">
                        &times;
                    </button>

                </div>


                {{-- FORM --}}
                <form id="rentalForm" class="p-6">

                    <input type="hidden" id="rentalId">


                    {{-- CUSTOMER + COURIER --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Customer <span class="text-red-500">*</span>
                            </label>

                            <select id="customer_id" name="customer_id" required
                                class="w-full border-gray-300 rounded-lg">

                                <option value="">
                                    -- Pilih Customer --
                                </option>

                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->customer_code }} -
                                        {{ $customer->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Kurir
                            </label>

                            <select id="courier_id" name="courier_id" class="w-full border-gray-300 rounded-lg">

                                <option value="">
                                    -- Tanpa Kurir --
                                </option>

                                @foreach ($couriers as $courier)
                                    <option value="{{ $courier->id }}">
                                        {{ $courier->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- DATE --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Mulai Rental <span class="text-red-500">*</span>
                            </label>

                            <input type="datetime-local" id="rental_start" name="rental_start" required
                                class="w-full border-gray-300 rounded-lg">

                        </div>


                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Selesai Rental <span class="text-red-500">*</span>
                            </label>

                            <input type="datetime-local" id="rental_end" name="rental_end" required
                                class="w-full border-gray-300 rounded-lg">

                        </div>

                    </div>


                    {{-- =====================================================
                    PRODUCTS
                ====================================================== --}}
                    <div class="border rounded-xl p-4 mb-6">

                        <div class="flex items-center justify-between mb-4">

                            <div>

                                <h3 class="font-semibold text-gray-800">
                                    Produk Rental
                                </h3>

                                <p class="text-xs text-gray-500">
                                    Jumlah = unit barang. Durasi dihitung otomatis dari periode rental.
                                </p>

                            </div>


                            <button type="button" onclick="addRentalItem()"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm">
                                + Tambah Produk
                            </button>

                        </div>


                        <div id="rentalItems" class="space-y-3"></div>

                    </div>


                    {{-- FINANCIAL --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Diskon
                            </label>

                            <input type="number" id="discount" name="discount" value="0" min="0"
                                step="0.01" class="w-full border-gray-300 rounded-lg" oninput="calculateTotal()">

                        </div>


                        <div>

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Deposit / Jaminan
                            </label>

                            <input type="number" id="deposit" name="deposit" value="0" min="0"
                                step="0.01" class="w-full border-gray-300 rounded-lg">

                        </div>

                    </div>


                    {{-- NOTES --}}
                    <div class="mb-6">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan
                        </label>

                        <textarea id="notes" name="notes" rows="3" class="w-full border-gray-300 rounded-lg"
                            placeholder="Catatan transaksi..."></textarea>

                    </div>


                    {{-- SUMMARY --}}
                    <div class="bg-gray-50 rounded-xl p-5 mb-6">

                        <div class="flex justify-between mb-2">

                            <span class="text-gray-600">
                                Subtotal
                            </span>

                            <span id="subtotalDisplay" class="font-medium">
                                Rp 0
                            </span>

                        </div>


                        <div class="flex justify-between mb-2">

                            <span class="text-gray-600">
                                Diskon
                            </span>

                            <span id="discountDisplay" class="font-medium">
                                Rp 0
                            </span>

                        </div>


                        <div class="border-t pt-3 mt-3 flex justify-between">

                            <span class="font-bold text-gray-800">
                                TOTAL
                            </span>

                            <span id="totalDisplay" class="text-xl font-bold text-indigo-600">
                                Rp 0
                            </span>

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="mb-6">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Status
                        </label>

                        <select id="status" name="status" class="w-full md:w-1/2 border-gray-300 rounded-lg">

                            <option value="pending">
                                Pending
                            </option>

                            <option value="active">
                                Active
                            </option>

                        </select>

                    </div>


                    {{-- BUTTON --}}
                    <div class="flex justify-end gap-3">

                        <button type="button" onclick="closeRentalModal()"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>


                        <button type="submit" id="saveButton"
                            class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium">
                            Simpan Rental
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =========================================================
    JAVASCRIPT
========================================================== --}}

    <script>
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');

        const csrfToken = csrfMeta ?
            csrfMeta.getAttribute('content') :
            '';

        let editingRentalId = null;


        /* =========================================================
           FORMAT RUPIAH
        ========================================================= */

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(value || 0);
        }


        /* =========================================================
           LOAD RENTALS
        ========================================================= */

        async function loadRentals(page = 1) {
            const search = document
                .getElementById('search')
                .value;

            const status = document
                .getElementById('statusFilter')
                .value;

            const params = new URLSearchParams({
                page: page,
                search: search,
                status: status
            });

            try {

                const response = await fetch(
                    `{{ route('rentals.index') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal mengambil data rental.');
                }

                renderRentals(result);

            } catch (error) {

                console.error(error);

                document.getElementById('rentalTableBody').innerHTML = `
            <tr>
                <td
                    colspan="6"
                    class="px-6 py-8 text-center text-red-500"
                >
                    Gagal mengambil data rental.
                </td>
            </tr>
        `;
            }
        }


        /* =========================================================
           RENDER TABLE
        ========================================================= */

        function renderRentals(result) {
            const tbody = document.getElementById('rentalTableBody');

            if (!result.data || result.data.length === 0) {

                tbody.innerHTML = `
            <tr>
                <td
                    colspan="6"
                    class="px-6 py-8 text-center text-gray-500"
                >
                    Belum ada transaksi rental.
                </td>
            </tr>
        `;

                document.getElementById('pagination').innerHTML = '';

                return;
            }


            tbody.innerHTML = result.data.map(rental => {

                const statusClass = {
                    pending: 'bg-yellow-100 text-yellow-700',
                    active: 'bg-blue-100 text-blue-700',
                    returned: 'bg-green-100 text-green-700',
                    cancelled: 'bg-red-100 text-red-700'
                };


                const statusLabel = {
                    pending: 'Pending',
                    active: 'Active',
                    returned: 'Returned',
                    cancelled: 'Cancelled'
                };


                const start = formatDateTimeDisplay(
                    rental.rental_start
                );

                const end = formatDateTimeDisplay(
                    rental.rental_end
                );


                return `
            <tr class="hover:bg-gray-50">

                <td class="px-6 py-4 whitespace-nowrap">

                    <div class="font-semibold text-gray-800">
                        ${escapeHtml(rental.rental_code)}
                    </div>

                    <div class="text-xs text-gray-400">
                        ${rental.items?.length || 0} produk
                    </div>

                </td>


                <td class="px-6 py-4">

                    <div class="font-medium text-gray-800">
                        ${escapeHtml(rental.customer?.name || '-')}
                    </div>

                    <div class="text-xs text-gray-500">
                        ${escapeHtml(rental.customer?.customer_code || '')}
                    </div>

                </td>


                <td class="px-6 py-4 text-sm">

                    <div>
                        ${start}
                    </div>

                    <div class="text-gray-400">
                        s/d
                    </div>

                    <div>
                        ${end}
                    </div>

                </td>


                <td class="px-6 py-4 font-semibold">
                    ${formatRupiah(parseFloat(rental.total))}
                </td>


                <td class="px-6 py-4">

                    <span
                        class="px-2.5 py-1 rounded-full text-xs font-medium ${statusClass[rental.status] || 'bg-gray-100 text-gray-700'}"
                    >
                        ${statusLabel[rental.status] || rental.status}
                    </span>

                </td>


              <td class="px-6 py-4 text-right whitespace-nowrap">

    {{-- Invoice --}}
    <a
        href="/invoices/${rental.id}"
        target="_blank"
        class="text-indigo-600 hover:text-indigo-800 mr-3"
    >
        Invoice
    </a>


    ${
        ['pending', 'active'].includes(rental.status)
        ? `
                <button
                    type="button"
                    onclick='openEditModal(${JSON.stringify(rental)})'
                    class="text-indigo-600 hover:text-indigo-800 mr-3"
                >
                    Edit
                </button>
            `
        : ''
    }


    ${
        ['pending', 'cancelled'].includes(rental.status)
        ? `
                <button
                    type="button"
                    onclick="deleteRental(${rental.id})"
                    class="text-red-600 hover:text-red-800"
                >
                    Hapus
                </button>
            `
        : ''
    }

</td>
            </tr>
        `;

            }).join('');


            renderPagination(result);
        }


        /* =========================================================
           ESCAPE HTML
        ========================================================= */

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }


        /* =========================================================
           FORMAT DATETIME DISPLAY
        ========================================================= */

        function formatDateTimeDisplay(dateString) {
            if (!dateString) {
                return '-';
            }

            const date = new Date(dateString);

            if (Number.isNaN(date.getTime())) {
                return dateString;
            }

            return date.toLocaleString('id-ID');
        }


        /* =========================================================
           PAGINATION
        ========================================================= */

        function renderPagination(result) {
            const pagination = document.getElementById('pagination');

            if (!result.last_page || result.last_page <= 1) {

                pagination.innerHTML = '';

                return;
            }


            let html = `
        <div class="flex gap-2 flex-wrap justify-center">
    `;


            for (
                let page = 1; page <= result.last_page; page++
            ) {

                html += `
            <button
                type="button"
                onclick="loadRentals(${page})"
                class="px-3 py-1 rounded-lg border ${
                    page === result.current_page
                    ? 'bg-indigo-600 text-white'
                    : 'bg-white text-gray-700'
                }"
            >
                ${page}
            </button>
        `;
            }


            html += `</div>`;

            pagination.innerHTML = html;
        }


        /* =========================================================
           OPEN CREATE MODAL
        ========================================================= */

        function openCreateModal() {
            editingRentalId = null;

            document.getElementById('modalTitle')
                .textContent = 'Tambah Rental';

            document.getElementById('saveButton')
                .textContent = 'Simpan Rental';

            document.getElementById('rentalForm')
                .reset();

            document.getElementById('rentalId')
                .value = '';

            document.getElementById('discount')
                .value = 0;

            document.getElementById('deposit')
                .value = 0;

            document.getElementById('status')
                .value = 'pending';

            document.getElementById('rentalItems')
                .innerHTML = '';

            addRentalItem();

            document.getElementById('rentalModal')
                .classList.remove('hidden');

            calculateTotal();
        }


        /* =========================================================
           OPEN EDIT MODAL
        ========================================================= */

        function openEditModal(rental) {
            editingRentalId = rental.id;

            document.getElementById('modalTitle')
                .textContent = 'Edit Rental';

            document.getElementById('saveButton')
                .textContent = 'Update Rental';

            document.getElementById('rentalId')
                .value = rental.id;


            document.getElementById('customer_id')
                .value = rental.customer_id;


            document.getElementById('courier_id')
                .value = rental.courier_id || '';


            document.getElementById('rental_start')
                .value = formatDateTimeLocal(
                    rental.rental_start
                );


            document.getElementById('rental_end')
                .value = formatDateTimeLocal(
                    rental.rental_end
                );


            document.getElementById('discount')
                .value = rental.discount || 0;


            document.getElementById('deposit')
                .value = rental.deposit || 0;


            document.getElementById('notes')
                .value = rental.notes || '';


            document.getElementById('status')
                .value = rental.status;


            document.getElementById('rentalItems')
                .innerHTML = '';


            if (rental.items && rental.items.length > 0) {

                rental.items.forEach(item => {

                    addRentalItem(
                        item.product_id,
                        item.quantity,
                        item.pricing_type
                    );

                });

            } else {

                addRentalItem();

            }


            document.getElementById('rentalModal')
                .classList.remove('hidden');

            calculateTotal();
        }


        /* =========================================================
           FORMAT DATETIME LOCAL
        ========================================================= */

        function formatDateTimeLocal(dateString) {
            if (!dateString) {
                return '';
            }

            const date = new Date(dateString);

            if (Number.isNaN(date.getTime())) {
                return '';
            }

            const pad = num =>
                String(num).padStart(2, '0');


            return `${date.getFullYear()}-${
        pad(date.getMonth() + 1)
    }-${
        pad(date.getDate())
    }T${
        pad(date.getHours())
    }:${
        pad(date.getMinutes())
    }`;
        }


        /* =========================================================
           CLOSE MODAL
        ========================================================= */

        function closeRentalModal() {
            document.getElementById('rentalModal')
                .classList.add('hidden');
        }


        /* =========================================================
           ADD RENTAL ITEM
        ========================================================= */

        function addRentalItem(
            selectedProduct = '',
            quantity = 1,
            pricingType = 'day'
        ) {
            const container =
                document.getElementById('rentalItems');


            const itemId =
                Date.now() + Math.random();


            const productOptions = `
        <option value="">
            -- Pilih Produk --
        </option>

        @foreach ($products as $product)

    <option
        value="{{ $product->id }}"
        data-day="{{ $product->price_per_day }}"
        data-hour="{{ $product->price_per_hour }}"
        data-stock="{{ $product->available_stock }}"
        data-total-stock="{{ $product->stock }}"
        data-rented-stock="{{ $product->rented_stock }}"
        data-status="{{ $product->status }}"
    >
        {{ $product->code }} -
        {{ $product->name }}
        (Tersedia: {{ $product->available_stock }})
    </option>

@endforeach
    `;


            const html = `
        <div
            class="rental-item border rounded-lg p-3"
            data-item="${itemId}"
        >

            <div class="grid grid-cols-1 md:grid-cols-6 gap-3 items-end">

                {{-- PRODUK --}}
                <div class="md:col-span-2">

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Produk
                    </label>

                    <select
                        class="product-select w-full border-gray-300 rounded-lg text-sm"
                        onchange="onProductChange(this)"
                    >
                        ${productOptions}
                    </select>

                </div>


                {{-- TIPE --}}
                <div>

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Tipe
                    </label>

                    <select
                        class="pricing-type w-full border-gray-300 rounded-lg text-sm"
                        onchange="onPricingTypeChange(this)"
                    >

                        <option value="day">
                            Per Hari
                        </option>

                        <option value="hour">
                            Per Jam
                        </option>

                    </select>

                </div>


                {{-- JUMLAH --}}
                <div>

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Jumlah
                    </label>

                    <input
                        type="number"
                        min="1"
                        step="1"
                        value="${Math.max(1, parseInt(quantity) || 1)}"
                        class="quantity w-full border-gray-300 rounded-lg text-sm"
                        oninput="calculateTotal()"
                    >

                </div>


                {{-- DURASI --}}
                <div>

                    <label class="block text-xs font-medium text-gray-600 mb-1">
                        Durasi
                    </label>

                    <div
                        class="duration-display w-full border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-600"
                    >
                        -
                    </div>

                </div>


                {{-- HAPUS --}}
                <div>

                    <button
                        type="button"
                        onclick="removeRentalItem(this)"
                        class="w-full px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-sm"
                    >
                        Hapus
                    </button>

                </div>

            </div>


            {{-- PRICE + SUBTOTAL --}}
            <div class="mt-3 flex flex-col md:flex-row md:justify-between gap-2 text-sm">

                <span class="text-gray-500">

                    Harga:

                    <span class="unit-price font-medium text-gray-700">
                        Rp 0
                    </span>

                </span>


                <span class="text-gray-500">

                    Subtotal:

                    <span class="item-subtotal font-semibold text-gray-800">
                        Rp 0
                    </span>

                </span>

            </div>

        </div>
    `;


            container.insertAdjacentHTML(
                'beforeend',
                html
            );


            const item =
                container.querySelector(
                    `[data-item="${itemId}"]`
                );


            const productSelect =
                item.querySelector('.product-select');


            const pricingSelect =
                item.querySelector('.pricing-type');


            productSelect.value =
                selectedProduct;


            pricingSelect.value =
                pricingType;


            if (selectedProduct) {

                onProductChange(
                    productSelect
                );

            } else {

                updateDurationDisplay(
                    item,
                    pricingType
                );

            }

        }


        /* =========================================================
           PRODUCT CHANGE
        ========================================================= */

        function onProductChange(select) {
            const item =
                select.closest('.rental-item');


            const option =
                select.options[
                    select.selectedIndex
                ];


            if (!option || !option.value) {

                item.querySelector('.unit-price')
                    .textContent = formatRupiah(0);

                updateDurationDisplay(
                    item,
                    item.querySelector('.pricing-type').value
                );

                calculateTotal();

                return;
            }


            if (option.dataset.status === 'maintenance') {

                alert(
                    'Produk ini sedang maintenance dan tidak dapat disewa.'
                );

                select.value = '';

                item.querySelector('.unit-price')
                    .textContent = formatRupiah(0);

                calculateTotal();

                return;
            }


            const pricingType =
                item.querySelector('.pricing-type').value;


            const price =
                pricingType === 'hour' ?
                parseFloat(option.dataset.hour || 0) :
                parseFloat(option.dataset.day || 0);


            item.querySelector('.unit-price')
                .textContent = formatRupiah(price);


            updateDurationDisplay(
                item,
                pricingType
            );


            calculateTotal();
        }


        /* =========================================================
           PRICING TYPE CHANGE
        ========================================================= */

        function onPricingTypeChange(select) {
            const item =
                select.closest('.rental-item');


            const productSelect =
                item.querySelector('.product-select');


            if (productSelect.value) {

                onProductChange(
                    productSelect
                );

            } else {

                updateDurationDisplay(
                    item,
                    select.value
                );

                calculateTotal();
            }
        }


        /* =========================================================
           UPDATE DURATION DISPLAY
        ========================================================= */

        function updateDurationDisplay(
            item,
            pricingType,
            hours = null,
            days = null
        ) {
            const display =
                item.querySelector(
                    '.duration-display'
                );


            if (!display) {
                return;
            }


            if (
                hours === null ||
                days === null
            ) {

                const duration =
                    calculateRentalDuration();


                hours = duration.hours;
                days = duration.days;
            }


            display.textContent =
                pricingType === 'hour' ?
                `${hours} jam` :
                `${days} hari`;
        }


        /* =========================================================
           CALCULATE RENTAL DURATION
        ========================================================= */

        function calculateRentalDuration() {
            const startValue =
                document.getElementById('rental_start').value;


            const endValue =
                document.getElementById('rental_end').value;


            let hours = 1;
            let days = 1;


            if (startValue && endValue) {

                const start =
                    new Date(startValue);


                const end =
                    new Date(endValue);


                const diffMinutes =
                    (end - start) / 60000;


                if (diffMinutes > 0) {

                    hours =
                        Math.max(
                            1,
                            Math.ceil(
                                diffMinutes / 60
                            )
                        );


                    days =
                        Math.max(
                            1,
                            Math.ceil(
                                diffMinutes / 1440
                            )
                        );

                }

            }


            return {
                hours: hours,
                days: days
            };
        }


        /* =========================================================
           REMOVE ITEM
        ========================================================= */

        function removeRentalItem(button) {
            const item =
                button.closest('.rental-item');


            if (!item) {
                return;
            }


            item.remove();

            calculateTotal();
        }


        /* =========================================================
           CALCULATE TOTAL
        ========================================================= */

        function calculateTotal() {
            const duration =
                calculateRentalDuration();


            const hours =
                duration.hours;


            const days =
                duration.days;


            let subtotal = 0;


            document
                .querySelectorAll('.rental-item')
                .forEach(item => {


                    const productSelect =
                        item.querySelector(
                            '.product-select'
                        );


                    const option =
                        productSelect.options[
                            productSelect.selectedIndex
                        ];


                    const pricingType =
                        item.querySelector(
                            '.pricing-type'
                        ).value;


                    const quantityInput =
                        item.querySelector(
                            '.quantity'
                        );


                    let quantity =
                        parseInt(
                            quantityInput.value || 1
                        );


                    if (
                        Number.isNaN(quantity) ||
                        quantity < 1
                    ) {

                        quantity = 1;

                        quantityInput.value = 1;
                    }


                    updateDurationDisplay(
                        item,
                        pricingType,
                        hours,
                        days
                    );


                    if (
                        !option ||
                        !option.value
                    ) {

                        item.querySelector(
                                '.unit-price'
                            ).textContent =
                            formatRupiah(0);


                        item.querySelector(
                                '.item-subtotal'
                            ).textContent =
                            formatRupiah(0);


                        return;
                    }


                    const price =
                        pricingType === 'hour' ?
                        parseFloat(
                            option.dataset.hour || 0
                        ) :
                        parseFloat(
                            option.dataset.day || 0
                        );


                    const itemDuration =
                        pricingType === 'hour' ?
                        hours :
                        days;


                    const itemSubtotal =
                        price *
                        itemDuration *
                        quantity;


                    item.querySelector(
                            '.unit-price'
                        ).textContent =
                        formatRupiah(price);


                    item.querySelector(
                            '.item-subtotal'
                        ).textContent =
                        formatRupiah(
                            itemSubtotal
                        );


                    subtotal += itemSubtotal;

                });


            const discount =
                Math.max(
                    0,
                    parseFloat(
                        document.getElementById(
                            'discount'
                        ).value || 0
                    )
                );


            const total =
                Math.max(
                    0,
                    subtotal - discount
                );


            document.getElementById(
                    'subtotalDisplay'
                ).textContent =
                formatRupiah(subtotal);


            document.getElementById(
                    'discountDisplay'
                ).textContent =
                formatRupiah(discount);


            document.getElementById(
                    'totalDisplay'
                ).textContent =
                formatRupiah(total);
        }


        /* =========================================================
           SUBMIT FORM
        ========================================================= */

        document
            .getElementById('rentalForm')
            .addEventListener(
                'submit',
                async function(event) {

                    event.preventDefault();


                    const startValue =
                        document.getElementById(
                            'rental_start'
                        ).value;


                    const endValue =
                        document.getElementById(
                            'rental_end'
                        ).value;


                    if (
                        !startValue ||
                        !endValue
                    ) {

                        alert(
                            'Tanggal mulai dan selesai wajib diisi.'
                        );

                        return;
                    }


                    const start =
                        new Date(startValue);


                    const end =
                        new Date(endValue);


                    if (end <= start) {

                        alert(
                            'Tanggal selesai harus setelah tanggal mulai.'
                        );

                        return;
                    }


                    const items = [];


                    document
                        .querySelectorAll(
                            '.rental-item'
                        )
                        .forEach(item => {


                            const productId =
                                item.querySelector(
                                    '.product-select'
                                ).value;


                            const quantity =
                                parseInt(
                                    item.querySelector(
                                        '.quantity'
                                    ).value || 1
                                );


                            const pricingType =
                                item.querySelector(
                                    '.pricing-type'
                                ).value;


                            if (productId) {

                                items.push({

                                    product_id: productId,

                                    quantity: Math.max(
                                        1,
                                        quantity
                                    ),

                                    pricing_type: pricingType

                                });

                            }

                        });


                    if (items.length === 0) {

                        alert(
                            'Minimal pilih 1 produk.'
                        );

                        return;
                    }


                    const data = {

                        customer_id: document.getElementById(
                            'customer_id'
                        ).value,


                        courier_id: document.getElementById(
                            'courier_id'
                        ).value || null,


                        rental_start: startValue,


                        rental_end: endValue,


                        discount: document.getElementById(
                            'discount'
                        ).value || 0,


                        deposit: document.getElementById(
                            'deposit'
                        ).value || 0,


                        status: document.getElementById(
                            'status'
                        ).value,


                        notes: document.getElementById(
                            'notes'
                        ).value,


                        items: items

                    };


                    const button =
                        document.getElementById(
                            'saveButton'
                        );


                    button.disabled = true;

                    button.textContent =
                        'Menyimpan...';


                    try {

                        let url =
                            '{{ route('rentals.store') }}';


                        let method = 'POST';


                        if (editingRentalId) {

                            url =
                                `/rentals/${editingRentalId}`;


                            data._method =
                                'PUT';

                        }


                        const response =
                            await fetch(
                                url, {

                                    method: method,

                                    headers: {

                                        'Content-Type': 'application/json',

                                        'X-CSRF-TOKEN': csrfToken,

                                        'X-Requested-With': 'XMLHttpRequest',

                                        'Accept': 'application/json'

                                    },

                                    body: JSON.stringify(data)

                                }
                            );


                        const result =
                            await response.json();


                        if (!response.ok) {

                            if (result.errors) {

                                const messages =
                                    Object.values(
                                        result.errors
                                    )
                                    .flat()
                                    .join('\n');


                                alert(messages);

                            } else {

                                alert(
                                    result.message ||
                                    'Terjadi kesalahan.'
                                );

                            }

                            return;
                        }


                        alert(
                            result.message ||
                            'Rental berhasil disimpan.'
                        );


                        closeRentalModal();

                        loadRentals();


                    } catch (error) {

                        console.error(error);


                        alert(
                            'Terjadi kesalahan saat menyimpan rental.'
                        );


                    } finally {

                        button.disabled = false;


                        button.textContent =
                            editingRentalId ?
                            'Update Rental' :
                            'Simpan Rental';

                    }

                }
            );


        /* =========================================================
           DELETE RENTAL
        ========================================================= */

        async function deleteRental(id) {
            if (
                !confirm(
                    'Yakin ingin menghapus rental ini?'
                )
            ) {

                return;
            }


            try {

                const response =
                    await fetch(
                        `/rentals/${id}`, {

                            method: 'DELETE',

                            headers: {

                                'X-CSRF-TOKEN': csrfToken,

                                'X-Requested-With': 'XMLHttpRequest',

                                'Accept': 'application/json'

                            }

                        }
                    );


                const result =
                    await response.json();


                if (!response.ok) {

                    alert(
                        result.message ||
                        'Gagal menghapus rental.'
                    );

                    return;
                }


                alert(
                    result.message ||
                    'Rental berhasil dihapus.'
                );


                loadRentals();


            } catch (error) {

                console.error(error);


                alert(
                    'Terjadi kesalahan saat menghapus rental.'
                );
            }
        }


        /* =========================================================
           DATE CHANGE
        ========================================================= */

        document
            .getElementById('rental_start')
            .addEventListener(
                'change',
                calculateTotal
            );


        document
            .getElementById('rental_end')
            .addEventListener(
                'change',
                calculateTotal
            );


        /* =========================================================
           SEARCH ENTER
        ========================================================= */

        document
            .getElementById('search')
            .addEventListener(
                'keydown',
                function(event) {

                    if (
                        event.key === 'Enter'
                    ) {

                        event.preventDefault();

                        loadRentals();
                    }

                }
            );


        /* =========================================================
           INITIAL LOAD
        ========================================================= */

        document.addEventListener(
            'DOMContentLoaded',
            function() {
                loadRentals();
            }
        );
    </script>

</x-app-layout>
