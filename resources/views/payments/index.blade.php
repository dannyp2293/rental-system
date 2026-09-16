<x-app-layout>

    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Pembayaran</h3>
            <p class="text-muted mb-0">
                Kelola pembayaran dan status tagihan rental
            </p>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cari Rental</label>
                    <input
                        type="text"
                        id="searchInput"
                        class="form-control"
                        placeholder="Kode rental / nama pelanggan / ID pelanggan..."
                    >
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Rental</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Sudah Dibayar</th>
                            <th>Sisa</th>
                            <th>Status</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="paymentTableBody">
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                Memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div id="paginationContainer" class="mt-3"></div>

        </div>
    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL PEMBAYARAN --}}
{{-- ========================================================= --}}

<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    Tambah Pembayaran
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <form id="paymentForm">

                    <input type="hidden" id="rental_id" name="rental_id">

                    {{-- INFO RENTAL --}}
                    <div class="alert alert-light border mb-4">
                        <div class="row">

                            <div class="col-md-4">
                                <small class="text-muted">
                                    Kode Rental
                                </small>

                                <div
                                    id="modalRentalCode"
                                    class="fw-bold">
                                    -
                                </div>
                            </div>

                            <div class="col-md-4">
                                <small class="text-muted">
                                    Pelanggan
                                </small>

                                <div
                                    id="modalCustomer"
                                    class="fw-bold">
                                    -
                                </div>
                            </div>

                            <div class="col-md-4">
                                <small class="text-muted">
                                    Sisa Tagihan
                                </small>

                                <div
                                    id="modalRemaining"
                                    class="fw-bold text-danger">
                                    Rp 0
                                </div>
                            </div>

                        </div>
                    </div>


                    {{-- TANGGAL --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Tanggal Pembayaran
                        </label>

                        <input
                            type="datetime-local"
                            class="form-control"
                            id="paid_at"
                            name="paid_at"
                            required>
                    </div>


                    {{-- JUMLAH --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Jumlah Pembayaran
                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="amount"
                            name="amount"
                            min="0.01"
                            step="0.01"
                            placeholder="Masukkan jumlah pembayaran"
                            required>

                        <small class="text-muted">
                            Tidak boleh melebihi sisa tagihan.
                        </small>
                    </div>


                    {{-- METODE --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Metode Pembayaran
                        </label>

                        <select
                            class="form-select"
                            id="method"
                            name="method"
                            required>

                            <option value="">
                                -- Pilih Metode --
                            </option>

                            <option value="cash">
                                Cash
                            </option>

                            <option value="transfer">
                                Transfer
                            </option>

                            <option value="qris">
                                QRIS
                            </option>

                            <option value="debit">
                                Debit
                            </option>

                            <option value="e_wallet">
                                E-Wallet
                            </option>

                        </select>
                    </div>


                    {{-- CATATAN --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Catatan
                        </label>

                        <textarea
                            class="form-control"
                            id="notes"
                            name="notes"
                            rows="3"
                            placeholder="Catatan pembayaran (opsional)">
                        </textarea>
                    </div>


                    {{-- ERROR --}}
                    <div
                        id="paymentError"
                        class="alert alert-danger d-none">
                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Batal
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="savePaymentBtn">

                    Simpan Pembayaran

                </button>

            </div>

        </div>
    </div>
</div>


{{-- ========================================================= --}}
{{-- MODAL RIWAYAT PEMBAYARAN --}}
{{-- ========================================================= --}}

<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title fw-bold">
                    Riwayat Pembayaran
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div id="historyContent">
                    Memuat...
                </div>

            </div>

        </div>

    </div>

</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const tableBody = document.getElementById('paymentTableBody');
    const paginationContainer =
        document.getElementById('paginationContainer');

    const searchInput =
        document.getElementById('searchInput');

    const paymentModalElement =
        document.getElementById('paymentModal');

    const historyModalElement =
        document.getElementById('historyModal');

    const paymentModal =
        new bootstrap.Modal(paymentModalElement);

    const historyModal =
        new bootstrap.Modal(historyModalElement);

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')
        .getAttribute('content');


    let currentPage = 1;


    // ========================================================
    // FORMAT RUPIAH
    // ========================================================

    function formatRupiah(number) {

        return 'Rp ' + Number(number || 0)
            .toLocaleString('id-ID');

    }


    // ========================================================
    // FORMAT TANGGAL
    // ========================================================

    function formatDate(dateString) {

        if (!dateString) {
            return '-';
        }

        const date = new Date(dateString);

        return date.toLocaleString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

    }


    // ========================================================
    // STATUS BADGE
    // ========================================================

    function statusBadge(status) {

        if (status === 'paid') {

            return `
                <span class="badge bg-success">
                    PAID
                </span>
            `;

        }

        if (status === 'partial') {

            return `
                <span class="badge bg-warning text-dark">
                    PARTIAL
                </span>
            `;

        }

        return `
            <span class="badge bg-danger">
                UNPAID
            </span>
        `;

    }


    // ========================================================
    // LOAD DATA
    // ========================================================

    function loadPayments(page = 1) {

        currentPage = page;

        tableBody.innerHTML = `
            <tr>
                <td colspan="8"
                    class="text-center py-4">
                    Memuat data...
                </td>
            </tr>
        `;


        const params = new URLSearchParams();

        params.append('page', page);

        if (searchInput.value.trim() !== '') {

            params.append(
                'search',
                searchInput.value.trim()
            );

        }


        fetch(`{{ route('payments.index') }}?${params.toString()}`, {

            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }

        })

        .then(async response => {

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || 'Gagal mengambil data.'
                );
            }

            return data;

        })

        .then(data => {

            renderTable(data);

            renderPagination(data);

        })

        .catch(error => {

            console.error(error);

            tableBody.innerHTML = `
                <tr>
                    <td colspan="8"
                        class="text-center text-danger py-4">
                        ${error.message}
                    </td>
                </tr>
            `;

        });

    }


    // ========================================================
    // RENDER TABLE
    // ========================================================

    function renderTable(response) {

        const data = response.data || [];

        if (data.length === 0) {

            tableBody.innerHTML = `
                <tr>
                    <td colspan="8"
                        class="text-center text-muted py-4">
                        Belum ada data rental.
                    </td>
                </tr>
            `;

            return;

        }


        let html = '';


        data.forEach((rental, index) => {

            const number =
                ((response.current_page - 1) * 10)
                + index + 1;


            html += `

                <tr>

                    <td>
                        ${number}
                    </td>

                    <td>
                        <span class="fw-semibold">
                            ${rental.rental_code}
                        </span>
                    </td>

                    <td>
                        ${rental.customer || '-'}
                    </td>

                    <td>
                        ${formatRupiah(rental.total)}
                    </td>

                    <td class="text-success fw-semibold">
                        ${formatRupiah(rental.paid)}
                    </td>

                    <td class="text-danger fw-semibold">
                        ${formatRupiah(rental.remaining)}
                    </td>

                    <td>
                        ${statusBadge(rental.status)}
                    </td>

                    <td>

                        <div class="d-flex gap-1">

                            ${
                                rental.remaining > 0
                                ?
                                `
                                <button
                                    class="btn btn-sm btn-primary"
                                    onclick='openPaymentModal(${JSON.stringify(rental)})'>
                                    Bayar
                                </button>
                                `
                                :
                                ''
                            }


                            <button
                                class="btn btn-sm btn-outline-secondary"
                                onclick='showHistory(${JSON.stringify(rental.payments)}, "${rental.rental_code}")'>
                                Riwayat
                            </button>

                        </div>

                    </td>

                </tr>

            `;

        });


        tableBody.innerHTML = html;

    }


    // ========================================================
    // PAGINATION
    // ========================================================

    function renderPagination(response) {

        const current = response.current_page;
        const last = response.last_page;


        if (last <= 1) {

            paginationContainer.innerHTML = '';

            return;

        }


        let html = `
            <div class="d-flex justify-content-between align-items-center">

                <small class="text-muted">
                    Total ${response.total} rental
                </small>

                <div class="btn-group">
        `;


        if (current > 1) {

            html += `
                <button
                    class="btn btn-outline-secondary btn-sm"
                    onclick="loadPayments(${current - 1})">
                    ‹
                </button>
            `;

        }


        for (
            let page = 1;
            page <= last;
            page++
        ) {

            html += `
                <button
                    class="btn ${
                        page === current
                        ? 'btn-primary'
                        : 'btn-outline-secondary'
                    } btn-sm"
                    onclick="loadPayments(${page})">
                    ${page}
                </button>
            `;

        }


        if (current < last) {

            html += `
                <button
                    class="btn btn-outline-secondary btn-sm"
                    onclick="loadPayments(${current + 1})">
                    ›
                </button>
            `;

        }


        html += `
                </div>
            </div>
        `;


        paginationContainer.innerHTML = html;

    }


    // ========================================================
    // OPEN PAYMENT MODAL
    // ========================================================

    window.openPaymentModal = function (rental) {

        document.getElementById('paymentForm').reset();

        document.getElementById('paymentError')
            .classList.add('d-none');


        document.getElementById('rental_id').value =
            rental.id;


        document.getElementById('modalRentalCode')
            .textContent =
            rental.rental_code;


        document.getElementById('modalCustomer')
            .textContent =
            rental.customer || '-';


        document.getElementById('modalRemaining')
            .textContent =
            formatRupiah(rental.remaining);


        document.getElementById('amount').value =
            rental.remaining;


        // waktu sekarang
        const now = new Date();

        const localDateTime =
            now.getFullYear() +
            '-' +
            String(now.getMonth() + 1).padStart(2, '0') +
            '-' +
            String(now.getDate()).padStart(2, '0') +
            'T' +
            String(now.getHours()).padStart(2, '0') +
            ':' +
            String(now.getMinutes()).padStart(2, '0');


        document.getElementById('paid_at').value =
            localDateTime;


        paymentModal.show();

    };


    // ========================================================
    // SAVE PAYMENT
    // ========================================================

    document.getElementById('savePaymentBtn')
        .addEventListener('click', function () {

            const button = this;

            const form =
                document.getElementById('paymentForm');

            const formData =
                new FormData(form);


            const errorBox =
                document.getElementById('paymentError');


            errorBox.classList.add('d-none');

            button.disabled = true;

            button.innerHTML = 'Menyimpan...';


            fetch(`{{ route('payments.store') }}`, {

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },

                body: formData

            })

            .then(async response => {

                const data =
                    await response.json();


                if (!response.ok) {

                    let message =
                        data.message ||
                        'Pembayaran gagal disimpan.';


                    if (data.errors) {

                        message =
                            Object.values(data.errors)
                            .flat()
                            .join('<br>');

                    }


                    throw new Error(message);

                }


                return data;

            })

            .then(data => {

                alert(
                    data.message ||
                    'Pembayaran berhasil disimpan.'
                );


                paymentModal.hide();

                loadPayments(currentPage);

            })

            .catch(error => {

                errorBox.innerHTML =
                    error.message;

                errorBox.classList.remove('d-none');

            })

            .finally(() => {

                button.disabled = false;

                button.innerHTML =
                    'Simpan Pembayaran';

            });

        });


    // ========================================================
    // SHOW HISTORY
    // ========================================================

    window.showHistory = function (
        payments,
        rentalCode
    ) {

        const historyContent =
            document.getElementById('historyContent');


        if (!payments || payments.length === 0) {

            historyContent.innerHTML = `

                <div class="text-center text-muted py-4">

                    Belum ada pembayaran
                    untuk rental
                    <strong>${rentalCode}</strong>.

                </div>

            `;

            historyModal.show();

            return;

        }


        let html = `

            <div class="mb-3">

                <strong>
                    Rental:
                </strong>

                ${rentalCode}

            </div>


            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Metode</th>
                            <th>Jumlah</th>
                            <th>Catatan</th>
                            <th width="80">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

        `;


        payments.forEach(payment => {

            html += `

                <tr>

                    <td>
                        ${payment.payment_code}
                    </td>

                    <td>
                        ${formatDate(payment.paid_at)}
                    </td>

                    <td>
                        ${formatMethod(payment.method)}
                    </td>

                    <td class="fw-semibold">
                        ${formatRupiah(payment.amount)}
                    </td>

                    <td>
                        ${payment.notes || '-'}
                    </td>

                    <td>

                        <button
                            class="btn btn-sm btn-outline-danger"
                            onclick="deletePayment(${payment.id})">
                            Hapus
                        </button>

                    </td>

                </tr>

            `;

        });


        html += `

                    </tbody>

                </table>

            </div>

        `;


        historyContent.innerHTML =
            html;


        historyModal.show();

    };


    // ========================================================
    // FORMAT METHOD
    // ========================================================

    function formatMethod(method) {

        const methods = {

            cash: 'Cash',

            transfer: 'Transfer',

            qris: 'QRIS',

            debit: 'Debit',

            e_wallet: 'E-Wallet'

        };


        return methods[method] || method;

    }


    // ========================================================
    // DELETE PAYMENT
    // ========================================================

    window.deletePayment = function (paymentId) {

        if (!confirm(
            'Yakin ingin menghapus pembayaran ini?'
        )) {
            return;
        }


        fetch(
            `{{ url('/payments') }}/${paymentId}`,
            {

                method: 'DELETE',

                headers: {

                    'X-CSRF-TOKEN':
                        csrfToken,

                    'X-Requested-With':
                        'XMLHttpRequest',

                    'Accept':
                        'application/json'

                }

            }
        )

        .then(async response => {

            const data =
                await response.json();


            if (!response.ok) {

                throw new Error(
                    data.message ||
                    'Gagal menghapus pembayaran.'
                );

            }


            return data;

        })

        .then(data => {

            alert(
                data.message ||
                'Pembayaran berhasil dihapus.'
            );


            historyModal.hide();

            loadPayments(currentPage);

        })

        .catch(error => {

            alert(error.message);

        });

    };


    // ========================================================
    // SEARCH DEBOUNCE
    // ========================================================

    let searchTimeout;


    searchInput.addEventListener(
        'input',
        function () {

            clearTimeout(searchTimeout);


            searchTimeout = setTimeout(() => {

                loadPayments(1);

            }, 400);

        }
    );


    // ========================================================
    // INITIAL LOAD
    // ========================================================

    loadPayments(1);


    // supaya pagination bisa akses function
    window.loadPayments = loadPayments;

});

</script>

</x-app-layout>