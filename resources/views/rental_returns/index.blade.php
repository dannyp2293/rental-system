<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Pengembalian Rental
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Proses pengembalian barang rental dan pencatatan denda.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert --}}
            <div id="alertBox"
                 class="hidden mb-4 rounded-lg px-4 py-3 text-sm">
            </div>

            {{-- Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">

                {{-- Header --}}
                <div class="p-5 border-b border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Daftar Rental
                            </h3>
                            <p class="text-sm text-gray-500">
                                Rental yang masih menunggu pengembalian.
                            </p>
                        </div>

                        <div class="w-full md:w-80">
                            <input
                                type="text"
                                id="searchInput"
                                placeholder="Cari kode rental / customer..."
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Kode Rental
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Customer
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Mulai
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Selesai
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Item
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody id="rentalTableBody"
                               class="bg-white divide-y divide-gray-200">
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div id="pagination"
                     class="px-6 py-4 border-t border-gray-100">
                </div>

            </div>
        </div>
    </div>

    {{-- =========================
        MODAL RETURN
    ========================== --}}
    <div id="returnModal"
         class="hidden fixed inset-0 z-50 overflow-y-auto">

        {{-- Overlay --}}
        <div class="fixed inset-0 bg-black/50"
             onclick="closeReturnModal()"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">

            <div class="relative bg-white w-full max-w-4xl rounded-2xl shadow-xl">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            Proses Pengembalian
                        </h3>

                        <p id="modalRentalInfo"
                           class="text-sm text-gray-500 mt-1">
                        </p>
                    </div>

                    <button type="button"
                            onclick="closeReturnModal()"
                            class="text-gray-400 hover:text-gray-600 text-2xl">
                        &times;
                    </button>
                </div>

                {{-- Form --}}
                <form id="returnForm">

                    <input type="hidden"
                           id="rental_id"
                           name="rental_id">

                    <div class="p-6 space-y-6">

                        {{-- Return Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal & Jam Pengembalian
                            </label>

                            <input
                                type="datetime-local"
                                id="returned_at"
                                name="returned_at"
                                required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                            <p id="lateInfo"
                               class="text-sm text-gray-500 mt-1">
                            </p>
                        </div>

                        {{-- Items --}}
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-800">
                                    Kondisi Barang
                                </h4>

                                <span class="text-xs text-gray-500">
                                    Semua barang wajib diproses
                                </span>
                            </div>

                            <div id="returnItems"
                                 class="space-y-4">
                            </div>
                        </div>

                        {{-- Fees --}}
                        <div class="border-t pt-5">

                            <h4 class="font-semibold text-gray-800 mb-4">
                                Denda
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- Late Fee --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Denda Keterlambatan
                                    </label>

                                    <input
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        id="late_fee"
                                        name="late_fee"
                                        value="0"
                                        class="fee-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                                {{-- Damage --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Total Kerusakan
                                    </label>

                                    <input
                                        type="number"
                                        id="damage_fee_display"
                                        value="0"
                                        readonly
                                        class="w-full rounded-lg border-gray-200 bg-gray-50"
                                    >
                                </div>

                                {{-- Lost --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Total Kehilangan
                                    </label>

                                    <input
                                        type="number"
                                        id="lost_fee_display"
                                        value="0"
                                        readonly
                                        class="w-full rounded-lg border-gray-200 bg-gray-50"
                                    >
                                </div>

                                {{-- Total --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Total Denda
                                    </label>

                                    <input
                                        type="number"
                                        id="total_fee_display"
                                        value="0"
                                        readonly
                                        class="w-full rounded-lg border-gray-200 bg-gray-50 font-semibold"
                                    >
                                </div>

                            </div>
                        </div>

                        {{-- Deposit --}}
                        <div class="border-t pt-5">

                            <h4 class="font-semibold text-gray-800 mb-4">
                                Deposit
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="text-xs text-gray-500">
                                        Deposit Awal
                                    </div>

                                    <div id="depositAmount"
                                         class="text-lg font-bold text-gray-800">
                                        Rp 0
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="text-xs text-gray-500">
                                        Potongan Deposit
                                    </div>

                                    <div id="depositDeduction"
                                         class="text-lg font-bold text-gray-800">
                                        Rp 0
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="text-xs text-gray-500">
                                        Deposit Dikembalikan
                                    </div>

                                    <div id="depositReturn"
                                         class="text-lg font-bold text-green-600">
                                        Rp 0
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan
                            </label>

                            <textarea
                                id="notes"
                                name="notes"
                                rows="3"
                                placeholder="Catatan pengembalian..."
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            ></textarea>
                        </div>

                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-2xl">

                        <button
                            type="button"
                            onclick="closeReturnModal()"
                            class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>

                        <button
                            type="submit"
                            id="submitReturnBtn"
                            class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                            Proses Pengembalian
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>


    {{-- =========================
        JAVASCRIPT
    ========================== --}}
    <script>

        let rentals = [];
        let currentRental = null;
        let currentPage = 1;

        document.addEventListener('DOMContentLoaded', function () {

            loadRentals();

            document.getElementById('searchInput')
                .addEventListener('input', debounce(function () {
                    currentPage = 1;
                    loadRentals();
                }, 400));

            document.getElementById('returnForm')
                .addEventListener('submit', submitReturn);

            document.getElementById('returned_at')
                .addEventListener('change', function () {
                    calculateLateHours();
                });

            document.getElementById('late_fee')
                .addEventListener('input', calculateDeposit);

        });


        // =========================
        // LOAD RENTALS
        // =========================

        async function loadRentals(page = currentPage) {

            currentPage = page;

            const search = document.getElementById('searchInput').value;

            try {

                const response = await fetch(
                    `{{ route('rental-returns.index') }}?search=${encodeURIComponent(search)}&page=${page}`,
                    {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

                const result = await response.json();

                rentals = result.data || [];

                renderTable(result);

            } catch (error) {

                showAlert(
                    'Gagal mengambil data rental.',
                    'error'
                );

                console.error(error);
            }
        }


        // =========================
        // RENDER TABLE
        // =========================

        function renderTable(result) {

            const tbody = document.getElementById('rentalTableBody');

            tbody.innerHTML = '';

            if (!result.data || result.data.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td colspan="7"
                            class="px-6 py-10 text-center text-gray-500">
                            Tidak ada rental yang perlu dikembalikan.
                        </td>
                    </tr>
                `;

                renderPagination(result);

                return;
            }

            result.data.forEach(rental => {

                const customerName =
                    rental.customer?.name ?? '-';

                const itemCount =
                    rental.items?.length ?? 0;

                const statusBadge = getStatusBadge(
                    rental.status
                );

                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-800">
                                ${escapeHtml(rental.rental_code)}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-800">
                                ${escapeHtml(customerName)}
                            </div>

                            <div class="text-xs text-gray-500">
                                ${escapeHtml(
                                    rental.customer?.customer_code ?? ''
                                )}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            ${formatDateTime(rental.rental_start)}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            ${formatDateTime(rental.rental_end)}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            ${itemCount} item
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            ${statusBadge}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">

                            <button
                                type="button"
                                onclick="openReturnModal(${rental.id})"
                                class="inline-flex items-center px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm hover:bg-indigo-700">
                                Pengembalian
                            </button>

                        </td>

                    </tr>
                `;
            });

            renderPagination(result);
        }


        // =========================
        // OPEN MODAL
        // =========================

        function openReturnModal(rentalId) {

            currentRental = rentals.find(
                rental => Number(rental.id) === Number(rentalId)
            );

            if (!currentRental) {
                showAlert(
                    'Data rental tidak ditemukan.',
                    'error'
                );
                return;
            }

            document.getElementById('rental_id').value =
                currentRental.id;

            document.getElementById('modalRentalInfo').innerHTML =
                `${escapeHtml(currentRental.rental_code)} — ${escapeHtml(currentRental.customer?.name ?? '-')}`;

            // Default returned_at = sekarang
            document.getElementById('returned_at').value =
                getCurrentDateTimeLocal();

            document.getElementById('late_fee').value = 0;

            document.getElementById('notes').value = '';

            renderReturnItems();

            updateDepositDisplay();

            calculateLateHours();

            document.getElementById('returnModal')
                .classList.remove('hidden');

            document.body.classList.add('overflow-hidden');
        }


        // =========================
        // CLOSE MODAL
        // =========================

        function closeReturnModal() {

            document.getElementById('returnModal')
                .classList.add('hidden');

            document.body.classList.remove('overflow-hidden');

            currentRental = null;
        }


        // =========================
        // RENDER ITEMS
        // =========================

        function renderReturnItems() {

            const container =
                document.getElementById('returnItems');

            container.innerHTML = '';

            if (!currentRental.items ||
                currentRental.items.length === 0) {

                container.innerHTML = `
                    <div class="rounded-lg bg-yellow-50 text-yellow-700 px-4 py-3">
                        Rental ini tidak memiliki item.
                    </div>
                `;

                return;
            }

            currentRental.items.forEach((item, index) => {

                const productName =
                    item.product?.name ?? 'Produk';

                const quantity =
                    item.quantity ?? 1;

                container.innerHTML += `
                    <div
                        class="border rounded-xl p-4"
                        data-item-id="${item.id}">

                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                            <div class="flex-1">

                                <div class="font-semibold text-gray-800">
                                    ${escapeHtml(productName)}
                                </div>

                                <div class="text-sm text-gray-500 mt-1">
                                    Jumlah: ${quantity}
                                </div>

                            </div>

                            <div class="w-full md:w-48">

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Kondisi
                                </label>

                                <select
                                    class="condition-input w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    data-index="${index}"
                                    onchange="handleConditionChange(${index})">

                                    <option value="good">
                                        Baik
                                    </option>

                                    <option value="damaged">
                                        Rusak
                                    </option>

                                    <option value="lost">
                                        Hilang
                                    </option>

                                </select>

                            </div>

                        </div>

                        <div
                            id="feeFields_${index}"
                            class="hidden grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Biaya Kerusakan
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    value="0"
                                    class="damage-input w-full rounded-lg border-gray-300"
                                    data-index="${index}"
                                    oninput="calculateFees()">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Biaya Kehilangan
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    value="0"
                                    class="lost-input w-full rounded-lg border-gray-300"
                                    data-index="${index}"
                                    oninput="calculateFees()">
                            </div>

                        </div>

                        <div class="mt-4">

                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan Item
                            </label>

                            <input
                                type="text"
                                class="item-notes w-full rounded-lg border-gray-300"
                                data-index="${index}"
                                placeholder="Contoh: lecet pada bagian samping...">

                        </div>

                    </div>
                `;
            });
        }


        // =========================
        // CONDITION CHANGE
        // =========================

        function handleConditionChange(index) {

            const select =
                document.querySelector(
                    `.condition-input[data-index="${index}"]`
                );

            const fields =
                document.getElementById(`feeFields_${index}`);

            if (select.value === 'good') {

                fields.classList.add('hidden');

                const damageInput =
                    document.querySelector(
                        `.damage-input[data-index="${index}"]`
                    );

                const lostInput =
                    document.querySelector(
                        `.lost-input[data-index="${index}"]`
                    );

                damageInput.value = 0;
                lostInput.value = 0;

            } else if (select.value === 'damaged') {

                fields.classList.remove('hidden');

                const lostInput =
                    document.querySelector(
                        `.lost-input[data-index="${index}"]`
                    );

                lostInput.value = 0;

            } else if (select.value === 'lost') {

                fields.classList.remove('hidden');

                const damageInput =
                    document.querySelector(
                        `.damage-input[data-index="${index}"]`
                    );

                damageInput.value = 0;
            }

            calculateFees();
        }


        // =========================
        // CALCULATE FEES
        // =========================

        function calculateFees() {

            let damageFee = 0;
            let lostFee = 0;

            document.querySelectorAll('.damage-input')
                .forEach(input => {

                    damageFee +=
                        parseFloat(input.value) || 0;
                });

            document.querySelectorAll('.lost-input')
                .forEach(input => {

                    lostFee +=
                        parseFloat(input.value) || 0;
                });

            document.getElementById('damage_fee_display').value =
                damageFee;

            document.getElementById('lost_fee_display').value =
                lostFee;

            const lateFee =
                parseFloat(
                    document.getElementById('late_fee').value
                ) || 0;

            const total =
                lateFee +
                damageFee +
                lostFee;

            document.getElementById('total_fee_display').value =
                total;

            calculateDeposit();
        }


        // =========================
        // CALCULATE LATE
        // =========================

        function calculateLateHours() {

            if (!currentRental) {
                return;
            }

            const returnedAtValue =
                document.getElementById('returned_at').value;

            if (!returnedAtValue) {
                return;
            }

            const returnedAt =
                new Date(returnedAtValue);

            const rentalEnd =
                new Date(
                    currentRental.rental_end
                        .replace(' ', 'T')
                );

            const diffMs =
                returnedAt.getTime() -
                rentalEnd.getTime();

            if (diffMs > 0) {

                const hours =
                    Math.ceil(
                        diffMs / (1000 * 60 * 60)
                    );

                document.getElementById('lateInfo').innerHTML =
                    `<span class="text-red-600 font-medium">
                        Terlambat ${hours} jam
                    </span>`;

            } else {

                document.getElementById('lateInfo').innerHTML =
                    `<span class="text-green-600">
                        Tidak terlambat
                    </span>`;
            }
        }


        // =========================
        // DEPOSIT
        // =========================

        function calculateDeposit() {

            if (!currentRental) {
                return;
            }

            const deposit =
                parseFloat(currentRental.deposit) || 0;

            const lateFee =
                parseFloat(
                    document.getElementById('late_fee').value
                ) || 0;

            let damageFee = 0;
            let lostFee = 0;

            document.querySelectorAll('.damage-input')
                .forEach(input => {
                    damageFee +=
                        parseFloat(input.value) || 0;
                });

            document.querySelectorAll('.lost-input')
                .forEach(input => {
                    lostFee +=
                        parseFloat(input.value) || 0;
                });

            const totalDeduction =
                lateFee +
                damageFee +
                lostFee;

            const deduction =
                Math.min(
                    deposit,
                    totalDeduction
                );

            const depositReturn =
                Math.max(
                    0,
                    deposit - deduction
                );

            document.getElementById('depositDeduction')
                .innerText =
                formatRupiah(deduction);

            document.getElementById('depositReturn')
                .innerText =
                formatRupiah(depositReturn);
        }


        function updateDepositDisplay() {

            const deposit =
                parseFloat(currentRental.deposit) || 0;

            document.getElementById('depositAmount')
                .innerText =
                formatRupiah(deposit);

            document.getElementById('depositDeduction')
                .innerText =
                formatRupiah(0);

            document.getElementById('depositReturn')
                .innerText =
                formatRupiah(deposit);
        }


        // =========================
        // SUBMIT RETURN
        // =========================

        async function submitReturn(event) {

            event.preventDefault();

            if (!currentRental) {
                return;
            }

            const submitButton =
                document.getElementById('submitReturnBtn');

            submitButton.disabled = true;
            submitButton.innerText = 'Memproses...';

            const items = [];

            currentRental.items.forEach((item, index) => {

                const condition =
                    document.querySelector(
                        `.condition-input[data-index="${index}"]`
                    ).value;

                const damageFee =
                    parseFloat(
                        document.querySelector(
                            `.damage-input[data-index="${index}"]`
                        )?.value || 0
                    );

                const lostFee =
                    parseFloat(
                        document.querySelector(
                            `.lost-input[data-index="${index}"]`
                        )?.value || 0
                    );

                const notes =
                    document.querySelector(
                        `.item-notes[data-index="${index}"]`
                    )?.value || '';

                items.push({
                    rental_item_id: item.id,
                    condition: condition,
                    damage_fee: damageFee,
                    lost_fee: lostFee,
                    notes: notes
                });
            });

            const payload = {

                rental_id:
                    currentRental.id,

                returned_at:
                    document.getElementById('returned_at').value,

                late_fee:
                    parseFloat(
                        document.getElementById('late_fee').value
                    ) || 0,

                notes:
                    document.getElementById('notes').value,

                items: items
            };

            try {

                const response = await fetch(
                    `{{ route('rental-returns.store') }}`,
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN':
                                document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).getAttribute('content'),

                            'Accept': 'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'
                        },

                        body: JSON.stringify(payload)
                    }
                );

                const result =
                    await response.json();

                if (!response.ok || !result.success) {

                    throw new Error(
                        result.message ||
                        'Pengembalian gagal diproses.'
                    );
                }

                closeReturnModal();

                showAlert(
                    result.message ||
                    'Pengembalian berhasil diproses.',
                    'success'
                );

                loadRentals(1);

            } catch (error) {

                showAlert(
                    error.message ||
                    'Terjadi kesalahan.',
                    'error'
                );

                console.error(error);

            } finally {

                submitButton.disabled = false;
                submitButton.innerText =
                    'Proses Pengembalian';
            }
        }


        // =========================
        // PAGINATION
        // =========================

        function renderPagination(result) {

            const pagination =
                document.getElementById('pagination');

            if (!result.last_page ||
                result.last_page <= 1) {

                pagination.innerHTML = `
                    <div class="text-sm text-gray-500">
                        Total ${result.total ?? 0} rental
                    </div>
                `;

                return;
            }

            let html = `
                <div class="flex items-center justify-between">

                    <div class="text-sm text-gray-500">
                        Total ${result.total} rental
                    </div>

                    <div class="flex gap-1">
            `;

            for (
                let page = 1;
                page <= result.last_page;
                page++
            ) {

                const active =
                    page === result.current_page;

                html += `
                    <button
                        type="button"
                        onclick="loadRentals(${page})"
                        class="px-3 py-1 rounded-lg text-sm ${
                            active
                            ? 'bg-indigo-600 text-white'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        }">
                        ${page}
                    </button>
                `;
            }

            html += `
                    </div>
                </div>
            `;

            pagination.innerHTML = html;
        }


        // =========================
        // STATUS
        // =========================

        function getStatusBadge(status) {

            const labels = {
                pending: 'Pending',
                active: 'Aktif',
                returned: 'Dikembalikan',
                cancelled: 'Dibatalkan'
            };

            const classes = {
                pending: 'bg-yellow-100 text-yellow-700',
                active: 'bg-blue-100 text-blue-700',
                returned: 'bg-green-100 text-green-700',
                cancelled: 'bg-red-100 text-red-700'
            };

            return `
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold ${
                    classes[status] ??
                    'bg-gray-100 text-gray-700'
                }">
                    ${labels[status] ?? status}
                </span>
            `;
        }


        // =========================
        // FORMAT
        // =========================

        function formatRupiah(value) {

            return new Intl.NumberFormat(
                'id-ID',
                {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }
            ).format(value || 0);
        }


        function formatDateTime(value) {

            if (!value) {
                return '-';
            }

            const date =
                new Date(
                    value.replace(' ', 'T')
                );

            if (isNaN(date.getTime())) {
                return value;
            }

            return date.toLocaleString(
                'id-ID',
                {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }
            );
        }


        function getCurrentDateTimeLocal() {

            const now = new Date();

            const year =
                now.getFullYear();

            const month =
                String(
                    now.getMonth() + 1
                ).padStart(2, '0');

            const day =
                String(
                    now.getDate()
                ).padStart(2, '0');

            const hours =
                String(
                    now.getHours()
                ).padStart(2, '0');

            const minutes =
                String(
                    now.getMinutes()
                ).padStart(2, '0');

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }


        // =========================
        // ALERT
        // =========================

        function showAlert(message, type = 'success') {

            const box =
                document.getElementById('alertBox');

            box.classList.remove(
                'hidden',
                'bg-green-100',
                'text-green-700',
                'bg-red-100',
                'text-red-700'
            );

            if (type === 'success') {

                box.classList.add(
                    'bg-green-100',
                    'text-green-700'
                );

            } else {

                box.classList.add(
                    'bg-red-100',
                    'text-red-700'
                );
            }

            box.innerText = message;

            setTimeout(() => {

                box.classList.add('hidden');

            }, 4000);
        }


        // =========================
        // DEBOUNCE
        // =========================

        function debounce(callback, delay) {

            let timeout;

            return function (...args) {

                clearTimeout(timeout);

                timeout = setTimeout(
                    () => callback.apply(this, args),
                    delay
                );
            };
        }


        // =========================
        // ESCAPE HTML
        // =========================

        function escapeHtml(value) {

            if (value === null ||
                value === undefined) {
                return '';
            }

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

    </script>

</x-app-layout>