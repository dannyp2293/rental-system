<x-app-layout>

    <div class="max-w-7xl mx-auto px-6 py-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Bonus Karyawan
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Atur skema bonus karyawan berdasarkan persentase atau per transaksi.
                </p>
            </div>

            <button
                type="button"
                onclick="openCreateModal()"
                class="inline-flex items-center justify-center px-4 py-2.5
                       bg-blue-600 hover:bg-blue-700 text-white font-semibold
                       rounded-lg shadow-sm transition"
            >
                + Tambah Pengaturan
            </button>
        </div>

        {{-- ALERT --}}
        <div
            id="alertBox"
            class="hidden mb-5 px-4 py-3 rounded-lg text-sm font-medium"
        ></div>

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">
                    Daftar Pengaturan Bonus
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Karyawan
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Tipe Bonus
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Nilai
                            </th>

                            <th class="px-6 py-3 text-left font-semibold text-gray-600">
                                Status
                            </th>

                            <th class="px-6 py-3 text-right font-semibold text-gray-600">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody
                        id="bonusTableBody"
                        class="divide-y divide-gray-100"
                    >
                        <tr>
                            <td
                                colspan="5"
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


    {{-- =========================
        MODAL CREATE / EDIT
    ========================== --}}
    <div
        id="bonusModal"
        class="hidden fixed inset-0 z-50 overflow-y-auto"
    >

        <div
            class="fixed inset-0 bg-black/50"
            onclick="closeBonusModal()"
        ></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">

            <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-xl">

                {{-- MODAL HEADER --}}
                <div class="flex items-center justify-between px-6 py-4 border-b">

                    <div>
                        <h3
                            id="modalTitle"
                            class="text-lg font-bold text-gray-800"
                        >
                            Tambah Pengaturan Bonus
                        </h3>

                        <p class="text-xs text-gray-500 mt-1">
                            Tentukan skema bonus karyawan.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="closeBonusModal()"
                        class="text-gray-400 hover:text-gray-700 text-2xl"
                    >
                        &times;
                    </button>

                </div>


                {{-- FORM --}}
                <form id="bonusForm">

                    <div class="px-6 py-5 space-y-5">

                        {{-- KARYAWAN --}}
                        <div>
                            <label
                                for="employee_id"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Karyawan
                            </label>

                            <select
                                id="employee_id"
                                name="employee_id"
                                required
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">
                                    -- Pilih Karyawan --
                                </option>
                            </select>

                            <p
                                id="employee_id_error"
                                class="hidden text-xs text-red-600 mt-1"
                            ></p>
                        </div>


                        {{-- TIPE BONUS --}}
                        <div>
                            <label
                                for="bonus_type"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Tipe Bonus
                            </label>

                            <select
                                id="bonus_type"
                                name="bonus_type"
                                required
                                onchange="updateBonusInput()"
                                class="w-full rounded-lg border-gray-300
                                       focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="percentage">
                                    Persentase
                                </option>

                                <option value="per_transaction">
                                    Per Transaksi
                                </option>
                            </select>
                        </div>


                        {{-- NILAI BONUS --}}
                        <div>

                            <label
                                for="bonus_value"
                                class="block text-sm font-semibold text-gray-700 mb-2"
                            >
                                Nilai Bonus
                            </label>

                            <div class="relative">

                                <span
                                    id="bonusPrefix"
                                    class="absolute left-3 top-1/2 -translate-y-1/2
                                           text-gray-500 font-medium"
                                >
                                    %
                                </span>

                                <input
                                    type="number"
                                    id="bonus_value"
                                    name="bonus_value"
                                    min="0"
                                    step="0.01"
                                    required
                                    placeholder="5"
                                    class="w-full rounded-lg border-gray-300
                                           focus:border-blue-500 focus:ring-blue-500
                                           pl-9"
                                />

                            </div>

                            <p
                                id="bonusHelp"
                                class="text-xs text-gray-500 mt-1"
                            >
                                Contoh: 5 berarti bonus 5% dari nilai transaksi.
                            </p>

                            <p
                                id="bonus_value_error"
                                class="hidden text-xs text-red-600 mt-1"
                            ></p>

                        </div>


                        {{-- ACTIVE --}}
                        <div class="flex items-center gap-3">

                            <input
                                type="checkbox"
                                id="active"
                                name="active"
                                value="1"
                                checked
                                class="rounded border-gray-300 text-blue-600
                                       focus:ring-blue-500"
                            >

                            <label
                                for="active"
                                class="text-sm font-medium text-gray-700"
                            >
                                Bonus aktif
                            </label>

                        </div>

                    </div>


                    {{-- MODAL FOOTER --}}
                    <div
                        class="flex justify-end gap-3 px-6 py-4
                               border-t bg-gray-50 rounded-b-2xl"
                    >

                        <button
                            type="button"
                            onclick="closeBonusModal()"
                            class="px-4 py-2 rounded-lg border border-gray-300
                                   text-gray-700 hover:bg-gray-100"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            id="saveBonusButton"
                            class="px-5 py-2 rounded-lg bg-blue-600
                                   hover:bg-blue-700 text-white font-semibold"
                        >
                            Simpan
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

        let bonusData = [];
        let editId = null;


        // =========================
        // CSRF
        // =========================
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');


        // =========================
        // LOAD DATA
        // =========================
        async function loadBonuses() {

            try {

                const response = await fetch('{{ route('bonuses.index') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!result.success) {
                    throw new Error('Gagal mengambil data bonus.');
                }

                bonusData = result.data || [];

                populateEmployees(result.employees || []);

                renderBonusTable();

            } catch (error) {

                console.error(error);

                showAlert(
                    'Gagal memuat data pengaturan bonus.',
                    'error'
                );

            }

        }


        // =========================
        // EMPLOYEE OPTIONS
        // =========================
        function populateEmployees(employees) {

            const select = document.getElementById('employee_id');

            select.innerHTML = `
                <option value="">
                    -- Pilih Karyawan --
                </option>
            `;

            employees.forEach(employee => {

                const option = document.createElement('option');

                option.value = employee.id;

                option.textContent =
                    `${employee.name} - ${employee.email}`;

                select.appendChild(option);

            });

        }


        // =========================
        // RENDER TABLE
        // =========================
        function renderBonusTable() {

            const tbody = document.getElementById('bonusTableBody');

            if (!bonusData.length) {

                tbody.innerHTML = `
                    <tr>
                        <td
                            colspan="5"
                            class="px-6 py-10 text-center text-gray-400"
                        >
                            Belum ada pengaturan bonus.
                        </td>
                    </tr>
                `;

                return;
            }


            tbody.innerHTML = bonusData.map(item => {

                let typeLabel = '';
                let valueLabel = '';

                if (item.bonus_type === 'percentage') {

                    typeLabel = 'Persentase';

                    valueLabel =
                        `${formatNumber(item.bonus_value)}%`;

                } else {

                    typeLabel = 'Per Transaksi';

                    valueLabel =
                        formatRupiah(item.bonus_value);

                }


                const status = item.active
                    ? `
                        <span
                            class="inline-flex items-center px-2.5 py-1
                                   rounded-full text-xs font-semibold
                                   bg-green-100 text-green-700"
                        >
                            Aktif
                        </span>
                    `
                    : `
                        <span
                            class="inline-flex items-center px-2.5 py-1
                                   rounded-full text-xs font-semibold
                                   bg-gray-100 text-gray-600"
                        >
                            Nonaktif
                        </span>
                    `;


                return `
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4">

                            <div class="font-semibold text-gray-800">
                                ${escapeHtml(item.employee_name)}
                            </div>

                            <div class="text-xs text-gray-500 mt-1">
                                ${escapeHtml(item.employee_email)}
                            </div>

                        </td>


                        <td class="px-6 py-4">

                            <span
                                class="inline-flex items-center px-2.5 py-1
                                       rounded-full text-xs font-medium
                                       bg-blue-50 text-blue-700"
                            >
                                ${typeLabel}
                            </span>

                        </td>


                        <td class="px-6 py-4 font-semibold text-gray-800">
                            ${valueLabel}
                        </td>


                        <td class="px-6 py-4">
                            ${status}
                        </td>


                        <td class="px-6 py-4 text-right">

                            <div class="flex justify-end gap-2">

                                <button
                                    type="button"
                                    onclick="openEditModal(${item.id})"
                                    class="px-3 py-1.5 rounded-lg
                                           bg-yellow-50 text-yellow-700
                                           hover:bg-yellow-100 text-xs
                                           font-semibold"
                                >
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    onclick="deleteBonus(${item.id})"
                                    class="px-3 py-1.5 rounded-lg
                                           bg-red-50 text-red-700
                                           hover:bg-red-100 text-xs
                                           font-semibold"
                                >
                                    Hapus
                                </button>

                            </div>

                        </td>

                    </tr>
                `;

            }).join('');

        }


        // =========================
        // CREATE MODAL
        // =========================
        function openCreateModal() {

            editId = null;

            document.getElementById('modalTitle').textContent =
                'Tambah Pengaturan Bonus';

            document.getElementById('saveBonusButton').textContent =
                'Simpan';

            document.getElementById('bonusForm').reset();

            document.getElementById('active').checked = true;

            document.getElementById('bonus_type').value =
                'percentage';

            updateBonusInput();

            clearErrors();

            document
                .getElementById('bonusModal')
                .classList.remove('hidden');

        }


        // =========================
        // EDIT MODAL
        // =========================
        function openEditModal(id) {

            const item = bonusData.find(
                bonus => Number(bonus.id) === Number(id)
            );

            if (!item) return;

            editId = item.id;

            document.getElementById('modalTitle').textContent =
                'Edit Pengaturan Bonus';

            document.getElementById('saveBonusButton').textContent =
                'Simpan Perubahan';

            document.getElementById('employee_id').value =
                item.employee_id;

            document.getElementById('bonus_type').value =
                item.bonus_type;

            document.getElementById('bonus_value').value =
                item.bonus_value;

            document.getElementById('active').checked =
                Boolean(item.active);

            updateBonusInput();

            clearErrors();

            document
                .getElementById('bonusModal')
                .classList.remove('hidden');

        }


        // =========================
        // CLOSE MODAL
        // =========================
        function closeBonusModal() {

            document
                .getElementById('bonusModal')
                .classList.add('hidden');

            editId = null;

            clearErrors();

        }


        // =========================
        // BONUS INPUT
        // =========================
        function updateBonusInput() {

            const type =
                document.getElementById('bonus_type').value;

            const prefix =
                document.getElementById('bonusPrefix');

            const input =
                document.getElementById('bonus_value');

            const help =
                document.getElementById('bonusHelp');


            if (type === 'percentage') {

                prefix.textContent = '%';

                input.placeholder = '5';

                help.textContent =
                    'Contoh: 5 berarti bonus 5% dari nilai transaksi.';

            } else {

                prefix.textContent = 'Rp';

                input.placeholder = '10000';

                help.textContent =
                    'Contoh: 10000 berarti bonus Rp10.000 per transaksi.';

            }

        }


        // =========================
        // SUBMIT FORM
        // =========================
        document
            .getElementById('bonusForm')
            .addEventListener('submit', async function(event) {

                event.preventDefault();

                clearErrors();

                const button =
                    document.getElementById('saveBonusButton');

                button.disabled = true;

                button.textContent =
                    editId ? 'Menyimpan...' : 'Menyimpan...';


                const payload = {

                    employee_id:
                        document.getElementById('employee_id').value,

                    bonus_type:
                        document.getElementById('bonus_type').value,

                    bonus_value:
                        document.getElementById('bonus_value').value,

                    active:
                        document.getElementById('active').checked ? 1 : 0

                };


                try {

                    let url = '{{ route('bonuses.store') }}';

                    let method = 'POST';


                    if (editId) {

                        url =
                            `/bonuses/${editId}`;

                        method = 'PUT';

                    }


                    const response = await fetch(url, {

                        method: method,

                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest',

                            'X-CSRF-TOKEN':
                                csrfToken

                        },

                        body:
                            JSON.stringify(payload)

                    });


                    const result =
                        await response.json();


                    if (!response.ok) {

                        if (response.status === 422) {

                            showValidationErrors(
                                result.errors || {}
                            );

                            if (result.message) {
                                showAlert(
                                    result.message,
                                    'error'
                                );
                            }

                            return;

                        }

                        throw new Error(
                            result.message ||
                            'Terjadi kesalahan.'
                        );

                    }


                    showAlert(
                        result.message ||
                        'Data berhasil disimpan.',
                        'success'
                    );

                    closeBonusModal();

                    await loadBonuses();


                } catch (error) {

                    console.error(error);

                    showAlert(
                        error.message ||
                        'Gagal menyimpan data.',
                        'error'
                    );

                } finally {

                    button.disabled = false;

                    button.textContent =
                        editId
                            ? 'Simpan Perubahan'
                            : 'Simpan';

                }

            });


        // =========================
        // DELETE
        // =========================
        async function deleteBonus(id) {

            const item = bonusData.find(
                bonus => Number(bonus.id) === Number(id)
            );

            if (!item) return;


            const confirmed = confirm(
                `Hapus pengaturan bonus untuk ${item.employee_name}?`
            );

            if (!confirmed) return;


            try {

                const response = await fetch(
                    `/bonuses/${id}`,
                    {

                        method: 'DELETE',

                        headers: {

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest',

                            'X-CSRF-TOKEN':
                                csrfToken

                        }

                    }
                );


                const result =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        result.message ||
                        'Gagal menghapus data.'
                    );

                }


                showAlert(
                    result.message ||
                    'Pengaturan bonus berhasil dihapus.',
                    'success'
                );

                await loadBonuses();


            } catch (error) {

                console.error(error);

                showAlert(
                    error.message ||
                    'Gagal menghapus data.',
                    'error'
                );

            }

        }


        // =========================
        // ALERT
        // =========================
        function showAlert(message, type = 'success') {

            const box =
                document.getElementById('alertBox');


            box.textContent = message;

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


        // =========================
        // VALIDATION ERRORS
        // =========================
        function showValidationErrors(errors) {

            Object.keys(errors).forEach(field => {

                const element =
                    document.getElementById(
                        `${field}_error`
                    );

                if (!element) return;

                element.textContent =
                    errors[field][0];

                element.classList.remove('hidden');

            });

        }


        // =========================
        // CLEAR ERRORS
        // =========================
        function clearErrors() {

            document
                .querySelectorAll('[id$="_error"]')
                .forEach(element => {

                    element.textContent = '';

                    element.classList.add('hidden');

                });

        }


        // =========================
        // RUPIAH
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


        // =========================
        // NUMBER
        // =========================
        function formatNumber(value) {

            return new Intl.NumberFormat(
                'id-ID',
                {
                    maximumFractionDigits: 2
                }
            ).format(value || 0);

        }


        // =========================
        // ESCAPE HTML
        // =========================
        function escapeHtml(value) {

            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');

        }


        // =========================
        // INITIAL LOAD
        // =========================
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                updateBonusInput();

                loadBonuses();

            }
        );

    </script>

</x-app-layout>