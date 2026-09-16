<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-gray-900">
                Customer
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Kelola data pelanggan rental.
            </p>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- ALERT --}}

            <div id="alert" class="mb-6 hidden rounded-xl px-4 py-3 text-sm font-medium"></div>


            {{-- CARD --}}

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">


                {{-- HEADER --}}

                <div class="border-b border-gray-100 px-6 py-5">

                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                        <div>

                            <h3 class="font-bold text-gray-900">
                                Daftar Customer
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">

                                Total
                                <span id="customerTotal">
                                    {{ $customers->total() }}
                                </span>
                                customer

                            </p>

                        </div>


                        <button type="button" onclick="openCreateModal()"
                            class="rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700">
                            + Tambah Customer
                        </button>

                    </div>

                </div>


                {{-- SEARCH --}}

                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">

                    <div class="relative max-w-md">

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                            </svg>

                        </div>


                        <input type="text" id="search" placeholder="Cari nama, ID customer, WhatsApp..."
                            class="w-full rounded-xl border-gray-300 py-2.5 pl-10 pr-4 text-sm focus:border-gray-900 focus:ring-gray-900">

                    </div>

                </div>


                {{-- TABLE --}}

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-gray-50">

                            <tr>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    #
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Customer
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    WhatsApp
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Jaminan ID
                                </th>

                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody id="customerTable" class="divide-y divide-gray-100 bg-white">

                            @forelse($customers as $customer)
                                <tr class="transition hover:bg-gray-50">

                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $customers->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-6 py-4">

                                        <div class="font-semibold text-gray-900">
                                            {{ $customer->name }}
                                        </div>

                                        <div class="text-xs text-gray-400">
                                            {{ $customer->customer_code }}
                                        </div>

                                    </td>

                                    <td class="px-6 py-4">

                                        <a href="https://wa.me/{{ $customer->whatsapp }}" target="_blank"
                                            rel="noopener noreferrer"
                                            class="font-semibold text-green-600 hover:text-green-800">
                                            💬 {{ $customer->whatsapp }}
                                        </a>

                                    </td>

                                    <td class="px-6 py-4">

                                        <div class="text-sm font-semibold text-gray-700">
                                            {{ $customer->guarantee_type ? strtoupper($customer->guarantee_type) : '-' }}
                                        </div>

                                        <div class="text-xs text-gray-400">
                                            {{ $customer->guarantee_number ?? '' }}
                                        </div>

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        <button type="button" onclick='openEditModal(@json($customer))'
                                            class="mr-3 text-sm font-semibold text-blue-600 hover:text-blue-800">
                                            Edit
                                        </button>

                                        <button type="button" onclick="deleteCustomer({{ $customer->id }})"
                                            class="text-sm font-semibold text-red-600 hover:text-red-800">
                                            Hapus
                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="px-6 py-16 text-center">

                                        <div class="text-sm text-gray-500">
                                            Belum ada customer.
                                        </div>

                                        <button onclick="openCreateModal()"
                                            class="mt-2 text-sm font-semibold text-gray-900">
                                            + Tambah customer pertama
                                        </button>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}

                <div id="pagination" class="border-t border-gray-100 px-6 py-4">

                    {{ $customers->links() }}

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================= --}}
    {{-- MODAL --}}
    {{-- ============================================= --}}

    <div id="customerModal" class="fixed inset-0 z-50 hidden">

        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>


        <div class="relative flex min-h-screen items-center justify-center p-4">

            <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">


                {{-- HEADER --}}

                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                    <div>

                        <h3 id="modalTitle" class="text-lg font-bold text-gray-900">
                            Tambah Customer
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Masukkan data pelanggan rental.
                        </p>

                    </div>


                    <button type="button" onclick="closeModal()"
                        class="rounded-lg p-2 text-2xl text-gray-400 hover:bg-gray-100">
                        &times;
                    </button>

                </div>


                {{-- FORM --}}

                <form id="customerForm" class="space-y-5 px-6 py-6">

                    <input type="hidden" id="customerId">


                    {{-- CODE --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            ID Customer
                        </label>

                        <input type="text" value="Otomatis dibuat sistem" disabled
                            class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-500">

                        <p class="mt-1 text-xs text-gray-400">
                            Contoh: CUS-00001
                        </p>

                    </div>


                    {{-- NAME --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Nama Lengkap
                        </label>

                        <input type="text" id="name" name="name" placeholder="Nama lengkap customer"
                            class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                            required>

                        <p id="nameError" class="mt-1 hidden text-xs text-red-600"></p>

                    </div>


                    {{-- WHATSAPP --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            No. WhatsApp
                        </label>

                        <input type="text" id="whatsapp" name="whatsapp" placeholder="081234567890"
                            class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                            required>

                        <p class="mt-1 text-xs text-gray-400">
                            Bisa menggunakan format 08..., 62..., atau +62...
                        </p>

                        <p id="whatsappError" class="mt-1 hidden text-xs text-red-600"></p>

                    </div>


                    {{-- ADDRESS --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Alamat
                        </label>

                        <textarea id="address" name="address" rows="3" placeholder="Alamat lengkap customer..."
                            class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"></textarea>

                    </div>


                    {{-- GUARANTEE --}}

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Jenis Jaminan ID
                            </label>

                            <select id="guarantee_type" name="guarantee_type"
                                class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">

                                <option value="">
                                    Tidak ada
                                </option>

                                <option value="ktp">
                                    KTP
                                </option>

                                <option value="sim">
                                    SIM
                                </option>

                                <option value="passport">
                                    Passport
                                </option>

                                <option value="other">
                                    Lainnya
                                </option>

                            </select>

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Nomor Jaminan ID
                            </label>

                            <input type="text" id="guarantee_number" name="guarantee_number"
                                placeholder="Nomor identitas"
                                class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">

                        </div>

                    </div>


                    {{-- NOTES --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Catatan
                        </label>

                        <textarea id="notes" name="notes" rows="3" placeholder="Catatan customer..."
                            class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"></textarea>

                    </div>


                    {{-- BUTTON --}}

                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">

                        <button type="button" onclick="closeModal()"
                            class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>

                        <button type="submit" id="submitButton"
                            class="rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-700">
                            Simpan Customer
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- ============================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ============================================= --}}

    <script>
        let editMode = false;

        let searchTimer;

        const modal =
            document.getElementById('customerModal');

        const form =
            document.getElementById('customerForm');


        // ==========================================
        // ALERT
        // ==========================================

        function showAlert(message, type = 'success') {
            const alert =
                document.getElementById('alert');

            alert.innerText = message;

            alert.className =
                'mb-6 rounded-xl px-4 py-3 text-sm font-medium';

            if (type === 'success') {

                alert.classList.add(
                    'bg-green-50',
                    'text-green-700'
                );

            } else {

                alert.classList.add(
                    'bg-red-50',
                    'text-red-700'
                );

            }

            setTimeout(() => {
                alert.classList.add('hidden');
            }, 3500);
        }


        // ==========================================
        // CREATE
        // ==========================================

        function openCreateModal() {
            editMode = false;

            form.reset();

            document.getElementById(
                'customerId'
            ).value = '';

            document.getElementById(
                'modalTitle'
            ).innerText = 'Tambah Customer';

            document.getElementById(
                'submitButton'
            ).innerText = 'Simpan Customer';

            clearErrors();

            modal.classList.remove('hidden');

            setTimeout(() => {

                document.getElementById(
                    'name'
                ).focus();

            }, 100);
        }


        // ==========================================
        // EDIT
        // ==========================================

        function openEditModal(customer) {
            editMode = true;

            document.getElementById(
                'customerId'
            ).value = customer.id;

            document.getElementById(
                'modalTitle'
            ).innerText = 'Edit Customer';

            document.getElementById(
                'submitButton'
            ).innerText = 'Update Customer';

            document.getElementById(
                'name'
            ).value = customer.name ?? '';

            document.getElementById(
                'whatsapp'
            ).value = customer.whatsapp ?? '';

            document.getElementById(
                'address'
            ).value = customer.address ?? '';

            document.getElementById(
                'guarantee_type'
            ).value = customer.guarantee_type ?? '';

            document.getElementById(
                'guarantee_number'
            ).value = customer.guarantee_number ?? '';

            document.getElementById(
                'notes'
            ).value = customer.notes ?? '';

            clearErrors();

            modal.classList.remove('hidden');
        }


        // ==========================================
        // CLOSE
        // ==========================================

        function closeModal() {
            modal.classList.add('hidden');

            form.reset();

            clearErrors();
        }


        // ==========================================
        // ERRORS
        // ==========================================

        function clearErrors() {
            document
                .querySelectorAll('[id$="Error"]')
                .forEach(element => {

                    element.innerText = '';

                    element.classList.add(
                        'hidden'
                    );

                });
        }


        function showValidationErrors(errors) {
            clearErrors();

            Object.keys(errors).forEach(field => {

                const element =
                    document.getElementById(
                        `${field}Error`
                    );

                if (element) {

                    element.innerText =
                        errors[field][0];

                    element.classList.remove(
                        'hidden'
                    );

                }

            });
        }


        // ==========================================
        // SUBMIT
        // ==========================================

        form.addEventListener(
            'submit',
            async function(e) {
                e.preventDefault();

                clearErrors();

                const id =
                    document.getElementById(
                        'customerId'
                    ).value;

                const url =
                    editMode ?
                    `/customers/${id}` :
                    '/customers';

                const data =
                    new FormData(form);

                if (editMode) {

                    data.append(
                        '_method',
                        'PUT'
                    );

                }

                const button =
                    document.getElementById(
                        'submitButton'
                    );

                button.disabled = true;

                button.innerText =
                    editMode ?
                    'Mengupdate...' :
                    'Menyimpan...';


                try {

                    const response =
                        await fetch(url, {

                            method: 'POST',

                            headers: {

                                'Accept': 'application/json',

                                'X-CSRF-TOKEN': document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .getAttribute(
                                        'content'
                                    )

                            },

                            body: data

                        });


                    const result =
                        await response.json();


                    if (!response.ok) {

                        if (result.errors) {

                            showValidationErrors(
                                result.errors
                            );

                        }

                        throw new Error(
                            result.message ??
                            'Terjadi kesalahan.'
                        );

                    }


                    closeModal();

                    showAlert(
                        result.message
                    );

                    loadCustomers();

                } catch (error) {

                    console.error(error);

                    showAlert(
                        error.message,
                        'error'
                    );

                } finally {

                    button.disabled = false;

                    button.innerText =
                        editMode ?
                        'Update Customer' :
                        'Simpan Customer';

                }

            }
        );


        // ==========================================
        // LOAD CUSTOMER
        // ==========================================

        async function loadCustomers(page = 1) {
            const search =
                document.getElementById(
                    'search'
                ).value;


            const params =
                new URLSearchParams();

            params.append(
                'page',
                page
            );

            if (search) {

                params.append(
                    'search',
                    search
                );

            }


            try {

                const response =
                    await fetch(
                        `/customers?${params.toString()}`, {

                            headers: {

                                'X-Requested-With': 'XMLHttpRequest',

                                'Accept': 'application/json'

                            }

                        }
                    );


                const result =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        'Gagal mengambil data customer.'
                    );

                }


                renderCustomers(
                    result.data,
                    result.current_page
                );


                renderPagination(
                    result.current_page,
                    result.last_page
                );


                document.getElementById(
                        'customerTotal'
                    ).innerText =
                    result.total;

            } catch (error) {

                console.error(error);

                showAlert(
                    error.message,
                    'error'
                );

            }
        }


        // ==========================================
        // RENDER TABLE
        // ==========================================

        function renderCustomers(
            customers,
            currentPage
        ) {

            const tbody =
                document.getElementById(
                    'customerTable'
                );


            if (!customers.length) {

                tbody.innerHTML = `

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-16 text-center"
                        >

                            <div class="text-sm text-gray-500">
                                Belum ada customer.
                            </div>

                            <button
                                onclick="openCreateModal()"
                                class="mt-2 text-sm font-semibold text-gray-900"
                            >
                                + Tambah customer pertama
                            </button>

                        </td>

                    </tr>

                `;

                return;
            }


            tbody.innerHTML =
                customers.map(
                    (customer, index) => {

                        const number =
                            ((currentPage - 1) * 10) +
                            index +
                            1;


                        const whatsapp =
                            customer.whatsapp ?? '';


                        const whatsappLink =
                            whatsapp ?
                            `https://wa.me/${whatsapp}` :
                            '#';


                        const guarantee =
                            customer.guarantee_type ?
                            (
                                customer.guarantee_type
                                .toUpperCase()
                            ) :
                            '-';


                        return `

                            <tr class="transition hover:bg-gray-50">

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    ${number}
                                </td>


                                <td class="px-6 py-4">

                                    <div>

                                        <div class="font-semibold text-gray-900">
                                            ${escapeHtml(customer.name)}
                                        </div>

                                        <div class="text-xs text-gray-400">
                                            ${escapeHtml(customer.customer_code)}
                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-4">

                                    ${
                                        whatsapp
                                        ? `
                                                <a
                                                    href="${whatsappLink}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="inline-flex items-center gap-2 font-semibold text-green-600 hover:text-green-800"
                                                >
                                                    <span>💬</span>
                                                    ${escapeHtml(whatsapp)}
                                                </a>
                                            `
                                        : `
                                                <span class="text-gray-400">
                                                    -
                                                </span>
                                            `
                                    }

                                </td>


                                <td class="px-6 py-4">

                                    <div class="text-sm font-semibold text-gray-700">
                                        ${escapeHtml(guarantee)}
                                    </div>

                                    <div class="text-xs text-gray-400">
                                        ${escapeHtml(customer.guarantee_number ?? '')}
                                    </div>

                                </td>


                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <button
                                        type="button"
                                        onclick='openEditModal(${JSON.stringify(customer)})'
                                        class="mr-3 text-sm font-semibold text-blue-600 hover:text-blue-800"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        onclick="deleteCustomer(${customer.id})"
                                        class="text-sm font-semibold text-red-600 hover:text-red-800"
                                    >
                                        Hapus
                                    </button>

                                </td>

                            </tr>

                        `;

                    }
                ).join('');
        }


        // ==========================================
        // PAGINATION
        // ==========================================

        function renderPagination(
            current,
            last
        ) {

            const container =
                document.getElementById(
                    'pagination'
                );


            if (last <= 1) {

                container.innerHTML = '';

                return;
            }


            let html = `
                <div class="flex items-center justify-between">
            `;


            html += `

                <button
                    onclick="loadCustomers(${current - 1})"
                    ${current <= 1 ? 'disabled' : ''}
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-40"
                >
                    Sebelumnya
                </button>

            `;


            html += `

                <span class="text-sm text-gray-500">
                    Halaman ${current} dari ${last}
                </span>

            `;


            html += `

                <button
                    onclick="loadCustomers(${current + 1})"
                    ${current >= last ? 'disabled' : ''}
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-40"
                >
                    Berikutnya
                </button>

            `;


            html += `
                </div>
            `;


            container.innerHTML = html;
        }


        // ==========================================
        // DELETE
        // ==========================================

        async function deleteCustomer(id) {
            if (
                !confirm(
                    'Yakin ingin menghapus customer ini?'
                )
            ) {

                return;

            }


            try {

                const response =
                    await fetch(
                        `/customers/${id}`, {

                            method: 'DELETE',

                            headers: {

                                'Accept': 'application/json',

                                'X-CSRF-TOKEN': document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .getAttribute(
                                        'content'
                                    )

                            }

                        }
                    );


                const result =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        result.message ??
                        'Gagal menghapus customer.'
                    );

                }


                showAlert(
                    result.message
                );


                loadCustomers();

            } catch (error) {

                console.error(error);

                showAlert(
                    error.message,
                    'error'
                );

            }
        }


        // ==========================================
        // SEARCH
        // ==========================================

        document
            .getElementById('search')
            .addEventListener(
                'input',
                function() {

                    clearTimeout(
                        searchTimer
                    );


                    searchTimer =
                        setTimeout(
                            () => {

                                loadCustomers();

                            },
                            400
                        );

                }
            );


        // ==========================================
        // ESC
        // ==========================================

        document.addEventListener(
            'keydown',
            function(e) {

                if (e.key === 'Escape') {

                    closeModal();

                }

            }
        );


        // ==========================================
        // ESCAPE HTML
        // ==========================================

        function escapeHtml(value) {
            if (value === null || value === undefined) {
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
