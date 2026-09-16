<x-app-layout>

    <div class="max-w-7xl mx-auto px-6 py-6">

        {{-- HEADER --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Rekap Bonus Karyawan
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Lihat total transaksi dan bonus berdasarkan periode dan karyawan.
            </p>
        </div>


        {{-- FILTER --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        id="start_date"
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>


                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Akhir
                    </label>

                    <input
                        type="date"
                        id="end_date"
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>


                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Karyawan
                    </label>

                    <select
                        id="employee_id"
                        class="w-full rounded-lg border-gray-300
                               focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">
                            Semua Karyawan
                        </option>
                    </select>
                </div>


                <div class="flex items-end">

                    <button
                        type="button"
                        onclick="loadReport()"
                        class="w-full px-4 py-2.5 rounded-lg
                               bg-blue-600 hover:bg-blue-700
                               text-white font-semibold transition"
                    >
                        Terapkan Filter
                    </button>

                </div>

            </div>

        </div>


        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

            {{-- TRANSAKSI --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500 mb-2">
                    Total Transaksi
                </div>

                <div
                    id="totalTransactions"
                    class="text-2xl font-bold text-gray-800"
                >
                    0
                </div>

            </div>


            {{-- OMZET --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500 mb-2">
                    Total Nilai Transaksi
                </div>

                <div
                    id="totalTransactionValue"
                    class="text-2xl font-bold text-gray-800"
                >
                    Rp0
                </div>

            </div>


            {{-- BONUS --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500 mb-2">
                    Total Bonus
                </div>

                <div
                    id="totalBonus"
                    class="text-2xl font-bold text-green-600"
                >
                    Rp0
                </div>

            </div>

        </div>


        {{-- ALERT --}}
        <div
            id="alertBox"
            class="hidden mb-5 px-4 py-3 rounded-lg text-sm font-medium"
        ></div>


        {{-- REKAP PER KARYAWAN --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-800">
                    Rekap Per Karyawan
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Karyawan
                            </th>

                            <th class="px-6 py-3 text-center font-semibold text-gray-600">
                                Jumlah Transaksi
                            </th>

                            <th class="px-6 py-3 text-right font-semibold text-gray-600">
                                Nilai Transaksi
                            </th>

                            <th class="px-6 py-3 text-right font-semibold text-gray-600">
                                Total Bonus
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        id="summaryTableBody"
                        class="divide-y divide-gray-100"
                    >

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-10 text-center text-gray-400"
                            >
                                Memuat data...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        {{-- DETAIL TRANSAKSI --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-800">
                    Detail Transaksi Bonus
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Tanggal
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Kode Rental
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Karyawan
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Customer
                            </th>

                            <th class="px-6 py-3 text-right font-semibold text-gray-600">
                                Nilai Transaksi
                            </th>

                            <th class="px-6 py-3 text-center font-semibold text-gray-600">
                                Skema
                            </th>

                            <th class="px-6 py-3 text-right font-semibold text-gray-600">
                                Bonus
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        id="detailTableBody"
                        class="divide-y divide-gray-100"
                    >

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-10 text-center text-gray-400"
                            >
                                Memuat data...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- JAVASCRIPT --}}
    <script>

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');


        // ==========================================
        // LOAD REPORT
        // ==========================================
        async function loadReport() {

            try {

                const params = new URLSearchParams();


                const startDate =
                    document.getElementById('start_date').value;

                const endDate =
                    document.getElementById('end_date').value;

                const employeeId =
                    document.getElementById('employee_id').value;


                if (startDate) {
                    params.append('start_date', startDate);
                }

                if (endDate) {
                    params.append('end_date', endDate);
                }

                if (employeeId) {
                    params.append('employee_id', employeeId);
                }


                const url =
                    '{{ route('bonuses.report') }}'
                    + (params.toString()
                        ? '?' + params.toString()
                        : '');


                const response = await fetch(url, {

                    headers: {

                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'

                    }

                });


                const result =
                    await response.json();


                if (!response.ok || !result.success) {

                    throw new Error(
                        result.message ||
                        'Gagal mengambil laporan bonus.'
                    );

                }


                renderEmployees(
                    result.employees || []
                );

                renderGrandTotal(
                    result.grand_total || {}
                );

                renderSummary(
                    result.summary || []
                );

                renderDetails(
                    result.details || []
                );


            } catch (error) {

                console.error(error);

                showAlert(
                    error.message ||
                    'Gagal memuat laporan bonus.',
                    'error'
                );

            }

        }


        // ==========================================
        // EMPLOYEE FILTER
        // ==========================================
        function renderEmployees(employees) {

            const select =
                document.getElementById('employee_id');

            const currentValue =
                select.value;


            select.innerHTML = `
                <option value="">
                    Semua Karyawan
                </option>
            `;


            employees.forEach(employee => {

                const option =
                    document.createElement('option');

                option.value =
                    employee.id;

                option.textContent =
                    employee.name;

                select.appendChild(option);

            });


            if (
                currentValue &&
                employees.some(
                    employee =>
                        String(employee.id)
                        === String(currentValue)
                )
            ) {

                select.value =
                    currentValue;

            }

        }


        // ==========================================
        // GRAND TOTAL
        // ==========================================
        function renderGrandTotal(total) {

            document.getElementById(
                'totalTransactions'
            ).textContent =
                formatNumber(
                    total.transaction_count || 0
                );


            document.getElementById(
                'totalTransactionValue'
            ).textContent =
                formatRupiah(
                    total.transaction_total || 0
                );


            document.getElementById(
                'totalBonus'
            ).textContent =
                formatRupiah(
                    total.bonus_total || 0
                );

        }


        // ==========================================
        // SUMMARY
        // ==========================================
        function renderSummary(summary) {

            const tbody =
                document.getElementById(
                    'summaryTableBody'
                );


            if (!summary.length) {

                tbody.innerHTML = `
                    <tr>
                        <td
                            colspan="4"
                            class="px-6 py-10 text-center text-gray-400"
                        >
                            Tidak ada data bonus.
                        </td>
                    </tr>
                `;

                return;

            }


            tbody.innerHTML =
                summary.map(item => `

                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4">

                            <div class="font-semibold text-gray-800">
                                ${escapeHtml(
                                    item.employee_name
                                )}
                            </div>

                        </td>


                        <td class="px-6 py-4 text-center">

                            ${formatNumber(
                                item.transaction_count
                            )}

                        </td>


                        <td class="px-6 py-4 text-right">

                            ${formatRupiah(
                                item.transaction_total
                            )}

                        </td>


                        <td class="px-6 py-4 text-right">

                            <span class="font-bold text-green-600">

                                ${formatRupiah(
                                    item.bonus_total
                                )}

                            </span>

                        </td>

                    </tr>

                `).join('');

        }


        // ==========================================
        // DETAILS
        // ==========================================
        function renderDetails(details) {

            const tbody =
                document.getElementById(
                    'detailTableBody'
                );


            if (!details.length) {

                tbody.innerHTML = `
                    <tr>
                        <td
                            colspan="7"
                            class="px-6 py-10 text-center text-gray-400"
                        >
                            Tidak ada transaksi.
                        </td>
                    </tr>
                `;

                return;

            }


            tbody.innerHTML =
                details.map(item => {

                    let scheme = '-';


                    if (
                        item.bonus_type ===
                        'percentage'
                    ) {

                        scheme =
                            `${formatNumber(
                                item.bonus_value
                            )}%`;

                    } else if (
                        item.bonus_type ===
                        'per_transaction'
                    ) {

                        scheme =
                            formatRupiah(
                                item.bonus_value
                            );

                    }


                    return `

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4 whitespace-nowrap">

                                ${formatDate(
                                    item.rental_start
                                )}

                            </td>


                            <td class="px-6 py-4">

                                <span class="font-semibold text-blue-600">

                                    ${escapeHtml(
                                        item.rental_code
                                    )}

                                </span>

                            </td>


                            <td class="px-6 py-4">

                                ${escapeHtml(
                                    item.employee_name
                                )}

                            </td>


                            <td class="px-6 py-4">

                                ${escapeHtml(
                                    item.customer_name
                                )}

                            </td>


                            <td class="px-6 py-4 text-right">

                                ${formatRupiah(
                                    item.transaction_value
                                )}

                            </td>


                            <td class="px-6 py-4 text-center">

                                <span
                                    class="inline-flex items-center
                                           px-2.5 py-1 rounded-full
                                           text-xs font-medium
                                           bg-blue-50 text-blue-700"
                                >

                                    ${scheme}

                                </span>

                            </td>


                            <td class="px-6 py-4 text-right">

                                <span
                                    class="font-bold text-green-600"
                                >

                                    ${formatRupiah(
                                        item.bonus_amount
                                    )}

                                </span>

                            </td>

                        </tr>

                    `;

                }).join('');

        }


        // ==========================================
        // FORMAT RUPIAH
        // ==========================================
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


        // ==========================================
        // FORMAT NUMBER
        // ==========================================
        function formatNumber(value) {

            return new Intl.NumberFormat(
                'id-ID',
                {
                    maximumFractionDigits: 0
                }
            ).format(value || 0);

        }


        // ==========================================
        // FORMAT DATE
        // ==========================================
        function formatDate(value) {

            if (!value) return '-';

            const date =
                new Date(value.replace(' ', 'T'));


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


        // ==========================================
        // ESCAPE HTML
        // ==========================================
        function escapeHtml(value) {

            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');

        }


        // ==========================================
        // ALERT
        // ==========================================
        function showAlert(message, type = 'success') {

            const box =
                document.getElementById('alertBox');


            box.textContent =
                message;


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


            setTimeout(() => {

                box.classList.add('hidden');

            }, 4000);

        }


        // ==========================================
        // INITIAL LOAD
        // ==========================================
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                loadReport();

            }
        );

    </script>

</x-app-layout>