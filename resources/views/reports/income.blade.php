<x-app-layout>

    <div class="px-6 py-6">

        {{-- HEADER --}}
        <div class="mb-6">

            <h1 class="text-2xl font-bold text-gray-900">
                Laporan Pendapatan
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Ringkasan uang yang benar-benar masuk dari pembayaran rental.
            </p>

        </div>


        {{-- ===================================================== --}}
        {{-- FILTER PERIODE --}}
        {{-- ===================================================== --}}

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">

            <div class="flex items-center justify-between mb-4">

                <div>

                    <h2 class="text-base font-semibold text-gray-900">
                        Periode Laporan
                    </h2>

                    <p class="text-sm text-gray-500">
                        Pilih periode untuk melihat pendapatan.
                    </p>

                </div>

                <button type="button" onclick="resetIncomeFilter()"
                    class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
                    Reset
                </button>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- DARI --}}

                <div>

                    <label for="income_date_from" class="block text-sm font-medium text-gray-700 mb-1">
                        Dari Tanggal
                    </label>

                    <input type="date" id="income_date_from"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                </div>


                {{-- SAMPAI --}}

                <div>

                    <label for="income_date_to" class="block text-sm font-medium text-gray-700 mb-1">
                        Sampai Tanggal
                    </label>

                    <input type="date" id="income_date_to"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">

                </div>


                {{-- BUTTON --}}

                <div class="flex items-end">

                    <button type="button" onclick="loadIncomeReport()"
                        class="w-full px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                        Tampilkan Laporan
                    </button>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- SUMMARY --}}
        {{-- ===================================================== --}}

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            {{-- PENDAPATAN --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500">
                    Total Pendapatan
                </div>

                <div id="incomeTotal" class="mt-2 text-2xl font-bold text-green-600">
                    Rp 0
                </div>

                <div class="mt-1 text-xs text-gray-400">
                    Total pembayaran masuk
                </div>

            </div>


            {{-- JUMLAH PEMBAYARAN --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500">
                    Jumlah Pembayaran
                </div>

                <div id="incomePaymentCount" class="mt-2 text-2xl font-bold text-blue-600">
                    0
                </div>

                <div class="mt-1 text-xs text-gray-400">
                    Transaksi pembayaran
                </div>

            </div>


            {{-- NILAI RENTAL --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500">
                    Nilai Rental
                </div>

                <div id="incomeRentalTotal" class="mt-2 text-2xl font-bold text-indigo-600">
                    Rp 0
                </div>

                <div class="mt-1 text-xs text-gray-400">
                    Total nilai rental
                </div>

            </div>


            {{-- PIUTANG --}}

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">

                <div class="text-sm text-gray-500">
                    Sisa Tagihan
                </div>

                <div id="incomeRemaining" class="mt-2 text-2xl font-bold text-red-600">
                    Rp 0
                </div>

                <div class="mt-1 text-xs text-gray-400">
                    Tagihan belum dibayar
                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PENDAPATAN HARIAN --}}
        {{-- ===================================================== --}}

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">

            <div class="px-5 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-900">
                    Pendapatan Harian
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Total pembayaran berdasarkan tanggal pembayaran.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left font-semibold text-gray-700">
                                No
                            </th>

                            <th class="px-5 py-3 text-left font-semibold text-gray-700">
                                Tanggal
                            </th>

                            <th class="px-5 py-3 text-right font-semibold text-gray-700">
                                Pendapatan
                            </th>

                        </tr>

                    </thead>


                    <tbody id="dailyIncomeBody" class="divide-y divide-gray-100">

                        <tr>

                            <td colspan="3" class="px-5 py-8 text-center text-gray-500">
                                Memuat data...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PENDAPATAN BULANAN --}}
        {{-- ===================================================== --}}

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">

            <div class="px-5 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-900">
                    Pendapatan Bulanan
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Total pembayaran berdasarkan bulan.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left font-semibold text-gray-700">
                                No
                            </th>

                            <th class="px-5 py-3 text-left font-semibold text-gray-700">
                                Bulan
                            </th>

                            <th class="px-5 py-3 text-right font-semibold text-gray-700">
                                Pendapatan
                            </th>

                        </tr>

                    </thead>


                    <tbody id="monthlyIncomeBody" class="divide-y divide-gray-100">

                        <tr>

                            <td colspan="3" class="px-5 py-8 text-center text-gray-500">
                                Memuat data...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

        {{-- ===================================================== --}}
        {{-- GRAFIK PENDAPATAN --}}
        {{-- ===================================================== --}}

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">

            <div class="px-5 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-900">
                    Grafik Pendapatan
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Pergerakan pendapatan berdasarkan tanggal pembayaran.
                </p>

            </div>

            <div class="p-5">

                <div id="incomeChart" class="w-full h-80 flex items-center justify-center text-gray-500">
                    Memuat grafik...
                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- PENDAPATAN TAHUNAN --}}
        {{-- ===================================================== --}}

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">

            <div class="px-5 py-4 border-b border-gray-100">

                <h2 class="font-semibold text-gray-900">
                    Pendapatan Tahunan
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Total pembayaran berdasarkan tahun.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-3 text-left font-semibold text-gray-700">
                                No
                            </th>

                            <th class="px-5 py-3 text-left font-semibold text-gray-700">
                                Tahun
                            </th>

                            <th class="px-5 py-3 text-right font-semibold text-gray-700">
                                Pendapatan
                            </th>

                        </tr>

                    </thead>


                    <tbody id="yearlyIncomeBody" class="divide-y divide-gray-100">

                        <tr>

                            <td colspan="3" class="px-5 py-8 text-center text-gray-500">
                                Memuat data...
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>
        let incomeChart = null;

        const monthNames = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];


        function formatIncomeRupiah(value) {
            return new Intl.NumberFormat(
                'id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }
            ).format(Number(value || 0));
        }


        function escapeIncomeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }


        function loadIncomeReport() {
            const dateFrom =
                document.getElementById(
                    'income_date_from'
                ).value;

            const dateTo =
                document.getElementById(
                    'income_date_to'
                ).value;


            const params =
                new URLSearchParams();


            if (dateFrom) {

                params.append(
                    'date_from',
                    dateFrom
                );

            }


            if (dateTo) {

                params.append(
                    'date_to',
                    dateTo
                );

            }


            document.getElementById(
                'dailyIncomeBody'
            ).innerHTML = `
                <tr>
                    <td
                        colspan="3"
                        class="px-5 py-8 text-center text-gray-500"
                    >
                        Memuat data...
                    </td>
                </tr>
            `;


            fetch(
                    `{{ route('reports.income') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',

                            'Accept': 'application/json'
                        }
                    }
                )
                .then(async response => {

                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Gagal memuat laporan pendapatan.'
                        );

                    }


                    return data;

                })
                .then(result => {

                    if (!result.success) {

                        throw new Error(
                            result.message ||
                            'Gagal memuat laporan pendapatan.'
                        );

                    }


                    renderIncomeSummary(
                        result.summary
                    );

                    renderIncomeChart(
                        result.daily
                    );

                    renderDailyIncome(
                        result.daily
                    );


                    renderMonthlyIncome(
                        result.monthly
                    );


                    renderYearlyIncome(
                        result.yearly
                    );

                })
                .catch(error => {

                    console.error(error);


                    document.getElementById(
                        'dailyIncomeBody'
                    ).innerHTML = `
                    <tr>
                        <td
                            colspan="3"
                            class="px-5 py-8 text-center text-red-600"
                        >
                            ${escapeIncomeHtml(
                                error.message
                            )}
                        </td>
                    </tr>
                `;

                });
        }


        function renderIncomeSummary(summary) {
            document.getElementById(
                    'incomeTotal'
                ).textContent =
                formatIncomeRupiah(
                    summary.total_income
                );


            document.getElementById(
                    'incomePaymentCount'
                ).textContent =
                Number(
                    summary.total_payments || 0
                );


            document.getElementById(
                    'incomeRentalTotal'
                ).textContent =
                formatIncomeRupiah(
                    summary.total_rental
                );


            document.getElementById(
                    'incomeRemaining'
                ).textContent =
                formatIncomeRupiah(
                    summary.total_remaining
                );
        }


        function renderDailyIncome(rows) {
            const body =
                document.getElementById(
                    'dailyIncomeBody'
                );


            if (!rows || rows.length === 0) {

                body.innerHTML = `
                    <tr>
                        <td
                            colspan="3"
                            class="px-5 py-8 text-center text-gray-500"
                        >
                            Tidak ada pembayaran pada periode ini.
                        </td>
                    </tr>
                `;

                return;
            }


            body.innerHTML =
                rows.map((row, index) => {

                    const date =
                        new Date(
                            row.date + 'T00:00:00'
                        );


                    const formattedDate =
                        date.toLocaleDateString(
                            'id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            }
                        );


                    return `
                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-3">
                                ${index + 1}
                            </td>

                            <td class="px-5 py-3">
                                ${escapeIncomeHtml(
                                    formattedDate
                                )}
                            </td>

                            <td class="px-5 py-3 text-right font-medium text-green-600">
                                ${formatIncomeRupiah(
                                    row.total
                                )}
                            </td>

                        </tr>
                    `;

                }).join('');
        }


        function renderMonthlyIncome(rows) {
            const body =
                document.getElementById(
                    'monthlyIncomeBody'
                );


            if (!rows || rows.length === 0) {

                body.innerHTML = `
                    <tr>
                        <td
                            colspan="3"
                            class="px-5 py-8 text-center text-gray-500"
                        >
                            Tidak ada data bulanan.
                        </td>
                    </tr>
                `;

                return;
            }


            body.innerHTML =
                rows.map((row, index) => {

                    const month =
                        Number(row.month);


                    const year =
                        Number(row.year);


                    const label =
                        `${monthNames[month - 1]} ${year}`;


                    return `
                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-3">
                                ${index + 1}
                            </td>

                            <td class="px-5 py-3">
                                ${escapeIncomeHtml(
                                    label
                                )}
                            </td>

                            <td class="px-5 py-3 text-right font-medium text-green-600">
                                ${formatIncomeRupiah(
                                    row.total
                                )}
                            </td>

                        </tr>
                    `;

                }).join('');
        }


        function renderYearlyIncome(rows) {
            const body =
                document.getElementById(
                    'yearlyIncomeBody'
                );


            if (!rows || rows.length === 0) {

                body.innerHTML = `
                    <tr>
                        <td
                            colspan="3"
                            class="px-5 py-8 text-center text-gray-500"
                        >
                            Tidak ada data tahunan.
                        </td>
                    </tr>
                `;

                return;
            }


            body.innerHTML =
                rows.map((row, index) => {

                    return `
                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-3">
                                ${index + 1}
                            </td>

                            <td class="px-5 py-3 font-medium">
                                ${escapeIncomeHtml(
                                    row.year
                                )}
                            </td>

                            <td class="px-5 py-3 text-right font-medium text-green-600">
                                ${formatIncomeRupiah(
                                    row.total
                                )}
                            </td>

                        </tr>
                    `;

                }).join('');
        }


        function resetIncomeFilter() {
            document.getElementById(
                'income_date_from'
            ).value = '';


            document.getElementById(
                'income_date_to'
            ).value = '';


            loadIncomeReport();
        }


        document.addEventListener(
            'DOMContentLoaded',
            function() {

                loadIncomeReport();

            }
        );

        function renderIncomeChart(rows) {
            const container =
                document.getElementById('incomeChart');

            if (!rows || rows.length === 0) {

                container.innerHTML = `
            <div class="text-center text-gray-500 py-10">
                Tidak ada data pendapatan pada periode ini.
            </div>
        `;

                return;
            }

            const labels = rows.map(row => {

                const date =
                    new Date(row.date + 'T00:00:00');

                return date.toLocaleDateString(
                    'id-ID', {
                        day: '2-digit',
                        month: 'short'
                    }
                );
            });

            const values = rows.map(row =>
                Number(row.total || 0)
            );

            container.innerHTML = `
        <canvas id="incomeChartCanvas"></canvas>
    `;

            const canvas =
                document.getElementById(
                    'incomeChartCanvas'
                );

            if (incomeChart) {
                incomeChart.destroy();
            }

            incomeChart = new Chart(
                canvas, {
                    type: 'line',

                    data: {
                        labels: labels,

                        datasets: [{
                            label: 'Pendapatan',

                            data: values,

                            borderWidth: 2,

                            tension: 0.3,

                            fill: true,

                            pointRadius: 4,

                            pointHoverRadius: 6
                        }]
                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        plugins: {

                            legend: {
                                display: false
                            },

                            tooltip: {

                                callbacks: {

                                    label: function(context) {
                                        return formatIncomeRupiah(
                                            context.raw
                                        );
                                    }

                                }

                            }

                        },

                        scales: {

                            y: {

                                beginAtZero: true,

                                ticks: {

                                    callback: function(value) {
                                        return formatIncomeRupiah(
                                            value
                                        );
                                    }

                                }

                            }

                        }

                    }

                }
            );
        }
    </script>

</x-app-layout>
