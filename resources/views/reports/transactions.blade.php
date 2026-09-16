<x-app-layout>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="px-6 py-6">

        <div class="mb-6">

            <h1 class="text-2xl font-bold text-gray-900">
                Laporan Transaksi
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Laporan seluruh transaksi rental dan pembayaran.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- SUMMARY --}}
        {{-- ===================================================== --}}

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            {{-- TOTAL TRANSAKSI --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500">
                    Total Transaksi
                </div>

                <div
                    id="summaryTransactions"
                    class="mt-2 text-2xl font-bold text-gray-900"
                >
                    0
                </div>

            </div>


            {{-- TOTAL RENTAL --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500">
                    Total Rental
                </div>

                <div
                    id="summaryRental"
                    class="mt-2 text-2xl font-bold text-blue-600"
                >
                    Rp 0
                </div>

            </div>


            {{-- TOTAL DIBAYAR --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500">
                    Total Dibayar
                </div>

                <div
                    id="summaryPaid"
                    class="mt-2 text-2xl font-bold text-green-600"
                >
                    Rp 0
                </div>

            </div>


            {{-- TOTAL PIUTANG --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500">
                    Sisa Tagihan
                </div>

                <div
                    id="summaryRemaining"
                    class="mt-2 text-2xl font-bold text-red-600"
                >
                    Rp 0
                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- FILTER --}}
        {{-- ===================================================== --}}

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">

                <div>

                    <h2 class="text-base font-semibold text-gray-900">
                        Filter Laporan
                    </h2>

                    <p class="text-sm text-gray-500">
                        Gunakan filter untuk mempersempit data transaksi.
                    </p>

                </div>

                <button
                    type="button"
                    onclick="resetFilters()"
                    class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50"
                >
                    Reset
                </button>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                {{-- SEARCH --}}

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Cari
                    </label>

                    <input
                        type="text"
                        id="search"
                        placeholder="Kode rental / nama customer / ID customer..."
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>


                {{-- DATE FROM --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Dari Tanggal
                    </label>

                    <input
                        type="date"
                        id="date_from"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>


                {{-- DATE TO --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Sampai Tanggal
                    </label>

                    <input
                        type="date"
                        id="date_to"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                </div>


                {{-- RENTAL STATUS --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status Rental
                    </label>

                    <select
                        id="rental_status"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Semua
                        </option>

                        <option value="pending">
                            Pending
                        </option>

                        <option value="active">
                            Active
                        </option>

                        <option value="returned">
                            Returned
                        </option>

                        <option value="cancelled">
                            Cancelled
                        </option>

                    </select>

                </div>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-4">

                {{-- PAYMENT STATUS --}}

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status Pembayaran
                    </label>

                    <select
                        id="payment_status"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    >

                        <option value="">
                            Semua
                        </option>

                        <option value="unpaid">
                            Unpaid
                        </option>

                        <option value="partial">
                            Partial
                        </option>

                        <option value="paid">
                            Paid
                        </option>

                    </select>

                </div>


                <div class="flex items-end">

                    <button
                        type="button"
                        onclick="loadReport(1)"
                        class="w-full px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700"
                    >
                        Tampilkan Laporan
                    </button>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- TABLE --}}
        {{-- ===================================================== --}}

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50 border-b border-gray-200">

                        <tr>

                            <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                No
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                Kode Rental
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                Tanggal
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                Customer
                            </th>

                            <th class="px-4 py-3 text-right font-semibold text-gray-700">
                                Total
                            </th>

                            <th class="px-4 py-3 text-right font-semibold text-gray-700">
                                Dibayar
                            </th>

                            <th class="px-4 py-3 text-right font-semibold text-gray-700">
                                Sisa
                            </th>

                            <th class="px-4 py-3 text-center font-semibold text-gray-700">
                                Rental
                            </th>

                            <th class="px-4 py-3 text-center font-semibold text-gray-700">
                                Pembayaran
                            </th>

                            <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                Karyawan
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        id="reportTableBody"
                        class="divide-y divide-gray-100"
                    >

                        <tr>

                            <td
                                colspan="10"
                                class="px-4 py-8 text-center text-gray-500"
                            >
                                Memuat data...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            <div
                id="pagination"
                class="px-4 py-4 border-t border-gray-100"
            ></div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>

        let reportSearchTimer = null;


        function formatRupiah(value)
        {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(Number(value || 0));
        }


        function escapeHtml(value)
        {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }


        function rentalStatusBadge(status)
        {
            const value = String(status || '').toUpperCase();

            let classes =
                'inline-flex px-2.5 py-1 rounded-full text-xs font-semibold ';

            if (value === 'ACTIVE') {

                classes +=
                    'bg-blue-100 text-blue-700';

            } else if (value === 'RETURNED') {

                classes +=
                    'bg-green-100 text-green-700';

            } else if (value === 'CANCELLED') {

                classes +=
                    'bg-red-100 text-red-700';

            } else {

                classes +=
                    'bg-yellow-100 text-yellow-700';
            }

            return `
                <span class="${classes}">
                    ${escapeHtml(value)}
                </span>
            `;
        }


        function paymentStatusBadge(status)
        {
            const value = String(status || '').toUpperCase();

            let classes =
                'inline-flex px-2.5 py-1 rounded-full text-xs font-semibold ';

            if (value === 'PAID') {

                classes +=
                    'bg-green-100 text-green-700';

            } else if (value === 'PARTIAL') {

                classes +=
                    'bg-yellow-100 text-yellow-700';

            } else {

                classes +=
                    'bg-red-100 text-red-700';
            }

            return `
                <span class="${classes}">
                    ${escapeHtml(value)}
                </span>
            `;
        }


        function loadReport(page = 1)
        {
            const params = new URLSearchParams();

            const search =
                document.getElementById('search').value.trim();

            const dateFrom =
                document.getElementById('date_from').value;

            const dateTo =
                document.getElementById('date_to').value;

            const rentalStatus =
                document.getElementById('rental_status').value;

            const paymentStatus =
                document.getElementById('payment_status').value;


            if (search) {
                params.append('search', search);
            }

            if (dateFrom) {
                params.append('date_from', dateFrom);
            }

            if (dateTo) {
                params.append('date_to', dateTo);
            }

            if (rentalStatus) {
                params.append(
                    'rental_status',
                    rentalStatus
                );
            }

            if (paymentStatus) {
                params.append(
                    'payment_status',
                    paymentStatus
                );
            }

            params.append('page', page);


            const tbody =
                document.getElementById('reportTableBody');

            tbody.innerHTML = `
                <tr>
                    <td
                        colspan="10"
                        class="px-4 py-8 text-center text-gray-500"
                    >
                        Memuat data...
                    </td>
                </tr>
            `;


            fetch(
                `{{ route('reports.transactions') }}?${params.toString()}`,
                {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }
            )
            .then(async response => {

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.message ||
                        'Gagal memuat laporan.'
                    );
                }

                return data;
            })
            .then(result => {

                if (!result.success) {
                    throw new Error(
                        result.message ||
                        'Gagal memuat laporan.'
                    );
                }


                renderTable(result.data);

                renderPagination(
                    result.current_page,
                    result.last_page
                );

                updateSummary(result.data);

            })
            .catch(error => {

                console.error(error);

                tbody.innerHTML = `
                    <tr>
                        <td
                            colspan="10"
                            class="px-4 py-8 text-center text-red-600"
                        >
                            ${escapeHtml(error.message)}
                        </td>
                    </tr>
                `;

            });
        }


        function renderTable(rows)
        {
            const tbody =
                document.getElementById('reportTableBody');


            if (!rows || rows.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td
                            colspan="10"
                            class="px-4 py-8 text-center text-gray-500"
                        >
                            Tidak ada data transaksi.
                        </td>
                    </tr>
                `;

                return;
            }


            tbody.innerHTML = rows.map((row, index) => {

                const number =
                    index + 1;


                const date =
                    row.rental_start
                        ? new Date(row.rental_start)
                            .toLocaleString('id-ID')
                        : '-';


                return `
                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3">
                            ${number}
                        </td>

                        <td class="px-4 py-3">

                            <div class="font-semibold text-gray-900">
                                ${escapeHtml(row.rental_code)}
                            </div>

                            <div class="text-xs text-gray-400">
                                ${escapeHtml(row.customer_code)}
                            </div>

                        </td>

                        <td class="px-4 py-3 whitespace-nowrap">
                            ${escapeHtml(date)}
                        </td>

                        <td class="px-4 py-3">

                            <div class="font-medium">
                                ${escapeHtml(row.customer)}
                            </div>

                        </td>

                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            ${formatRupiah(row.total)}
                        </td>

                        <td class="px-4 py-3 text-right whitespace-nowrap text-green-600 font-medium">
                            ${formatRupiah(row.paid)}
                        </td>

                        <td class="px-4 py-3 text-right whitespace-nowrap text-red-600 font-medium">
                            ${formatRupiah(row.remaining)}
                        </td>

                        <td class="px-4 py-3 text-center">
                            ${rentalStatusBadge(row.rental_status)}
                        </td>

                        <td class="px-4 py-3 text-center">
                            ${paymentStatusBadge(row.payment_status)}
                        </td>

                        <td class="px-4 py-3">
                            ${escapeHtml(row.employee)}
                        </td>

                    </tr>
                `;

            }).join('');
        }


        function updateSummary(rows)
        {
            const totalTransactions =
                rows.length;


            const totalRental =
                rows.reduce(
                    (sum, row) =>
                        sum + Number(row.total || 0),
                    0
                );


            const totalPaid =
                rows.reduce(
                    (sum, row) =>
                        sum + Number(row.paid || 0),
                    0
                );


            const totalRemaining =
                rows.reduce(
                    (sum, row) =>
                        sum + Number(row.remaining || 0),
                    0
                );


            document.getElementById(
                'summaryTransactions'
            ).textContent = totalTransactions;


            document.getElementById(
                'summaryRental'
            ).textContent =
                formatRupiah(totalRental);


            document.getElementById(
                'summaryPaid'
            ).textContent =
                formatRupiah(totalPaid);


            document.getElementById(
                'summaryRemaining'
            ).textContent =
                formatRupiah(totalRemaining);
        }


        function renderPagination(currentPage, lastPage)
        {
            const container =
                document.getElementById('pagination');


            if (!lastPage || lastPage <= 1) {

                container.innerHTML = '';

                return;
            }


            let html = `
                <div class="flex items-center justify-between">

                    <button
                        type="button"
                        onclick="loadReport(${currentPage - 1})"
                        ${currentPage <= 1 ? 'disabled' : ''}
                        class="px-3 py-2 rounded-lg border text-sm
                        ${currentPage <= 1
                            ? 'text-gray-300 cursor-not-allowed'
                            : 'text-gray-600 hover:bg-gray-50'}"
                    >
                        Sebelumnya
                    </button>


                    <span class="text-sm text-gray-500">
                        Halaman ${currentPage} dari ${lastPage}
                    </span>


                    <button
                        type="button"
                        onclick="loadReport(${currentPage + 1})"
                        ${currentPage >= lastPage ? 'disabled' : ''}
                        class="px-3 py-2 rounded-lg border text-sm
                        ${currentPage >= lastPage
                            ? 'text-gray-300 cursor-not-allowed'
                            : 'text-gray-600 hover:bg-gray-50'}"
                    >
                        Berikutnya
                    </button>

                </div>
            `;


            container.innerHTML = html;
        }


        function resetFilters()
        {
            document.getElementById('search').value = '';

            document.getElementById('date_from').value = '';

            document.getElementById('date_to').value = '';

            document.getElementById('rental_status').value = '';

            document.getElementById('payment_status').value = '';

            loadReport(1);
        }


        document.addEventListener(
            'DOMContentLoaded',
            function () {

                loadReport(1);


                document
                    .getElementById('search')
                    .addEventListener(
                        'input',
                        function () {

                            clearTimeout(
                                reportSearchTimer
                            );


                            reportSearchTimer =
                                setTimeout(
                                    function () {
                                        loadReport(1);
                                    },
                                    400
                                );
                        }
                    );


                document
                    .getElementById('date_from')
                    .addEventListener(
                        'change',
                        function () {
                            loadReport(1);
                        }
                    );


                document
                    .getElementById('date_to')
                    .addEventListener(
                        'change',
                        function () {
                            loadReport(1);
                        }
                    );


                document
                    .getElementById('rental_status')
                    .addEventListener(
                        'change',
                        function () {
                            loadReport(1);
                        }
                    );


                document
                    .getElementById('payment_status')
                    .addEventListener(
                        'change',
                        function () {
                            loadReport(1);
                        }
                    );

            }
        );

    </script>

</x-app-layout>