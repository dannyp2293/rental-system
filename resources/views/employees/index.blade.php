<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-gray-900">
                Karyawan
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Kelola pengguna dan jabatan karyawan rental.
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
                                Daftar Karyawan
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">

                                Total
                                <span id="employeeTotal">
                                    {{ $employees->total() }}
                                </span>
                                karyawan

                            </p>

                        </div>


                        <button type="button" onclick="openCreateModal()"
                            class="rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700">
                            + Tambah Karyawan
                        </button>

                    </div>

                </div>


                {{-- SEARCH + FILTER --}}

                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                                </svg>

                            </div>


                            <input type="text" id="search" placeholder="Cari nama atau email..."
                                class="w-full rounded-xl border-gray-300 py-2.5 pl-10 pr-4 text-sm focus:border-gray-900 focus:ring-gray-900">

                        </div>


                        <select id="roleFilter"
                            class="rounded-xl border-gray-300 py-2.5 text-sm focus:border-gray-900 focus:ring-gray-900">

                            <option value="">
                                Semua Jabatan
                            </option>

                            <option value="admin">
                                Admin
                            </option>

                            <option value="kurir">
                                Kurir
                            </option>

                            <option value="staff">
                                Staff
                            </option>

                        </select>

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
                                    Karyawan
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Jabatan
                                </th>

                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Akses
                                </th>

                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody id="employeeTable" class="divide-y divide-gray-100 bg-white">

                            @forelse($employees as $employee)
                                <tr class="transition hover:bg-gray-50">

                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $employees->firstItem() + $loop->index }}
                                    </td>


                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-900 text-sm font-bold text-white">
                                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                                            </div>

                                            <div>

                                                <div class="font-semibold text-gray-900">
                                                    {{ $employee->name }}
                                                </div>

                                                <div class="text-xs text-gray-400">
                                                    {{ $employee->email }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    <td class="px-6 py-4">

                                        @if ($employee->role === 'admin')
                                            <span
                                                class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">
                                                Admin
                                            </span>
                                        @elseif($employee->role === 'kurir')
                                            <span
                                                class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                Kurir
                                            </span>
                                        @else
                                            <span
                                                class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                Staff
                                            </span>
                                        @endif

                                    </td>


                                    <td class="px-6 py-4 text-sm text-gray-600">

                                        @if ($employee->role === 'admin')
                                            Full Access
                                        @elseif($employee->role === 'kurir')
                                            Operasional
                                        @else
                                            Operasional
                                        @endif

                                    </td>


                                    <td class="px-6 py-4 text-right">

                                        <button type="button" onclick='openEditModal(@json($employee))'
                                            class="mr-3 text-sm font-semibold text-blue-600 hover:text-blue-800">
                                            Edit
                                        </button>


                                        <button type="button" onclick="deleteEmployee({{ $employee->id }})"
                                            class="text-sm font-semibold text-red-600 hover:text-red-800">
                                            Hapus
                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="px-6 py-16 text-center">

                                        <div class="text-sm text-gray-500">
                                            Belum ada karyawan.
                                        </div>

                                        <button onclick="openCreateModal()"
                                            class="mt-2 text-sm font-semibold text-gray-900">
                                            + Tambah karyawan pertama
                                        </button>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}

                <div id="pagination" class="border-t border-gray-100 px-6 py-4">

                    {{ $employees->links() }}

                </div>

            </div>

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- MODAL --}}
    {{-- ====================================================== --}}

    <div id="employeeModal" class="fixed inset-0 z-50 hidden">

        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>


        <div class="relative flex min-h-screen items-center justify-center p-4">

            <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white shadow-2xl">


                {{-- HEADER --}}

                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                    <div>

                        <h3 id="modalTitle" class="text-lg font-bold text-gray-900">
                            Tambah Karyawan
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Buat akun untuk pengguna sistem rental.
                        </p>

                    </div>


                    <button type="button" onclick="closeModal()"
                        class="rounded-lg p-2 text-2xl text-gray-400 hover:bg-gray-100">
                        &times;
                    </button>

                </div>


                {{-- FORM --}}

                <form id="employeeForm" class="space-y-5 px-6 py-6">

                    <input type="hidden" id="employeeId">


                    {{-- NAME --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Nama Lengkap
                        </label>

                        <input type="text" id="name" name="name" placeholder="Nama karyawan"
                            class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                            required>

                        <p id="nameError" class="mt-1 hidden text-xs text-red-600"></p>

                    </div>


                    {{-- EMAIL --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Email
                        </label>

                        <input type="email" id="email" name="email" placeholder="karyawan@email.com"
                            class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                            required>

                        <p id="emailError" class="mt-1 hidden text-xs text-red-600"></p>

                    </div>


                    {{-- ROLE --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Jabatan
                        </label>

                        <select id="role" name="role"
                            class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                            required>

                            <option value="staff">
                                Staff
                            </option>

                            <option value="kurir">
                                Kurir
                            </option>

                            <option value="admin">
                                Admin
                            </option>

                        </select>


                        <p class="mt-1 text-xs text-gray-400">
                            Admin memiliki akses penuh terhadap sistem.
                        </p>

                        <p id="roleError" class="mt-1 hidden text-xs text-red-600"></p>

                    </div>


                    {{-- PASSWORD --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">

                            Password

                            <span id="passwordOptional" class="hidden font-normal text-gray-400">
                                (kosongkan jika tidak ingin mengubah)
                            </span>

                        </label>


                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                                class="w-full rounded-xl border-gray-300 pr-12 focus:border-gray-900 focus:ring-gray-900">

                            <button type="button" onclick="togglePassword('password', 'passwordEye')"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-gray-700"
                                tabindex="-1">
                                <svg id="passwordEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>


                        <p id="passwordError" class="mt-1 hidden text-xs text-red-600"></p>

                    </div>


                    {{-- CONFIRM PASSWORD --}}

                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Konfirmasi Password
                        </label>

                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Ulangi password"
                                class="w-full rounded-xl border-gray-300 pr-12 focus:border-gray-900 focus:ring-gray-900">

                            <button type="button"
                                onclick="togglePassword('password_confirmation', 'passwordConfirmationEye')"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-gray-700"
                                tabindex="-1">
                                <svg id="passwordConfirmationEye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>


                        <p id="password_confirmationError" class="mt-1 hidden text-xs text-red-600"></p>

                    </div>


                    {{-- BUTTON --}}

                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">

                        <button type="button" onclick="closeModal()"
                            class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>


                        <button type="submit" id="submitButton"
                            class="rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 disabled:opacity-50">
                            Simpan Karyawan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- JAVASCRIPT --}}
    {{-- ====================================================== --}}

    <script>
        let editMode = false;

        let searchTimer;

        const modal =
            document.getElementById('employeeModal');

        const form =
            document.getElementById('employeeForm');


        // ==========================================
        // ALERT
        // ==========================================

        function showAlert(
            message,
            type = 'success'
        ) {

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

                alert.classList.add(
                    'hidden'
                );

            }, 3500);
        }


        // ==========================================
        // CREATE
        // ==========================================

        function openCreateModal() {
            editMode = false;

            form.reset();

            document.getElementById(
                'employeeId'
            ).value = '';


            document.getElementById(
                    'modalTitle'
                ).innerText =
                'Tambah Karyawan';


            document.getElementById(
                    'submitButton'
                ).innerText =
                'Simpan Karyawan';


            document.getElementById(
                'passwordOptional'
            ).classList.add('hidden');


            document.getElementById(
                'password'
            ).required = true;


            clearErrors();

            modal.classList.remove(
                'hidden'
            );


            setTimeout(() => {

                document.getElementById(
                    'name'
                ).focus();

            }, 100);
        }


        // ==========================================
        // EDIT
        // ==========================================

        function openEditModal(employee) {
            editMode = true;


            document.getElementById(
                    'employeeId'
                ).value =
                employee.id;


            document.getElementById(
                    'modalTitle'
                ).innerText =
                'Edit Karyawan';


            document.getElementById(
                    'submitButton'
                ).innerText =
                'Update Karyawan';


            document.getElementById(
                    'name'
                ).value =
                employee.name ?? '';


            document.getElementById(
                    'email'
                ).value =
                employee.email ?? '';


            document.getElementById(
                    'role'
                ).value =
                employee.role ?? 'staff';


            document.getElementById(
                'password'
            ).value = '';


            document.getElementById(
                'password_confirmation'
            ).value = '';


            document.getElementById(
                'passwordOptional'
            ).classList.remove(
                'hidden'
            );


            document.getElementById(
                'password'
            ).required = false;


            clearErrors();


            modal.classList.remove(
                'hidden'
            );
        }


        // ==========================================
        // CLOSE
        // ==========================================

        function closeModal() {
            modal.classList.add(
                'hidden'
            );

            form.reset();

            clearErrors();
        }


        // ==========================================
        // ERROR
        // ==========================================

        function clearErrors() {
            document
                .querySelectorAll(
                    '[id$="Error"]'
                )
                .forEach(element => {

                    element.innerText = '';

                    element.classList.add(
                        'hidden'
                    );

                });
        }


        function showValidationErrors(errors) {
            clearErrors();


            Object.keys(errors)
                .forEach(field => {

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
                        'employeeId'
                    ).value;


                const url =
                    editMode ?
                    `/employees/${id}` :
                    '/employees';


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
                        await fetch(
                            url, {

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

                            }
                        );


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


                    loadEmployees();


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
                        'Update Karyawan' :
                        'Simpan Karyawan';

                }

            }
        );


        // ==========================================
        // LOAD EMPLOYEE
        // ==========================================

        async function loadEmployees(
            page = 1
        ) {

            const search =
                document.getElementById(
                    'search'
                ).value;


            const role =
                document.getElementById(
                    'roleFilter'
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


            if (role) {

                params.append(
                    'role',
                    role
                );

            }


            try {

                const response =
                    await fetch(
                        `/employees?${params.toString()}`, {

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
                        'Gagal mengambil data karyawan.'
                    );

                }


                renderEmployees(
                    result.data,
                    result.current_page
                );


                renderPagination(
                    result.current_page,
                    result.last_page
                );


                document.getElementById(
                        'employeeTotal'
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
        // RENDER
        // ==========================================

        function renderEmployees(
            employees,
            currentPage
        ) {

            const tbody =
                document.getElementById(
                    'employeeTable'
                );


            if (!employees.length) {

                tbody.innerHTML = `

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-16 text-center"
                        >

                            <div class="text-sm text-gray-500">
                                Belum ada karyawan.
                            </div>

                            <button
                                onclick="openCreateModal()"
                                class="mt-2 text-sm font-semibold text-gray-900"
                            >
                                + Tambah karyawan pertama
                            </button>

                        </td>

                    </tr>

                `;

                return;
            }


            tbody.innerHTML =
                employees.map(
                    (employee, index) => {


                        const number =
                            ((currentPage - 1) * 10) +
                            index +
                            1;


                        let roleLabel =
                            'Staff';


                        let roleClass =
                            'bg-gray-100 text-gray-700';


                        let access =
                            'Operasional';


                        if (
                            employee.role ===
                            'admin'
                        ) {

                            roleLabel =
                                'Admin';

                            roleClass =
                                'bg-purple-100 text-purple-700';

                            access =
                                'Full Access';

                        }


                        if (
                            employee.role ===
                            'kurir'
                        ) {

                            roleLabel =
                                'Kurir';

                            roleClass =
                                'bg-blue-100 text-blue-700';

                        }


                        const initial =
                            employee.name ?
                            employee.name
                            .charAt(0)
                            .toUpperCase() :
                            '?';


                        return `

                            <tr class="transition hover:bg-gray-50">

                                <td class="px-6 py-4 text-sm text-gray-500">
                                    ${number}
                                </td>


                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-900 text-sm font-bold text-white">
                                            ${initial}
                                        </div>

                                        <div>

                                            <div class="font-semibold text-gray-900">
                                                ${escapeHtml(employee.name)}
                                            </div>

                                            <div class="text-xs text-gray-400">
                                                ${escapeHtml(employee.email)}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <td class="px-6 py-4">

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold ${roleClass}">
                                        ${roleLabel}
                                    </span>

                                </td>


                                <td class="px-6 py-4 text-sm text-gray-600">
                                    ${access}
                                </td>


                                <td class="px-6 py-4 text-right">

                                    <button
                                        type="button"
                                        onclick='openEditModal(${JSON.stringify(employee)})'
                                        class="mr-3 text-sm font-semibold text-blue-600 hover:text-blue-800"
                                    >
                                        Edit
                                    </button>


                                    <button
                                        type="button"
                                        onclick="deleteEmployee(${employee.id})"
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

                container.innerHTML =
                    '';

                return;
            }


            container.innerHTML = `

                <div class="flex items-center justify-between">

                    <button
                        onclick="loadEmployees(${current - 1})"
                        ${current <= 1 ? 'disabled' : ''}
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-40"
                    >
                        Sebelumnya
                    </button>


                    <span class="text-sm text-gray-500">
                        Halaman ${current} dari ${last}
                    </span>


                    <button
                        onclick="loadEmployees(${current + 1})"
                        ${current >= last ? 'disabled' : ''}
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-40"
                    >
                        Berikutnya
                    </button>

                </div>

            `;
        }


        // ==========================================
        // DELETE
        // ==========================================

        async function deleteEmployee(id) {
            if (
                !confirm(
                    'Yakin ingin menghapus karyawan ini?'
                )
            ) {

                return;

            }


            try {

                const response =
                    await fetch(
                        `/employees/${id}`, {

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
                        'Gagal menghapus karyawan.'
                    );

                }


                showAlert(
                    result.message
                );


                loadEmployees();


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

                                loadEmployees();

                            },
                            400
                        );

                }
            );


        // ==========================================
        // ROLE FILTER
        // ==========================================

        document
            .getElementById('roleFilter')
            .addEventListener(
                'change',
                function() {

                    loadEmployees();

                }
            );


        // ==========================================
        // ESC
        // ==========================================

        document.addEventListener(
            'keydown',
            function(e) {

                if (
                    e.key === 'Escape'
                ) {

                    closeModal();

                }

            }
        );


        // ==========================================
        // ESCAPE HTML
        // ==========================================

        function escapeHtml(value) {
            if (
                value === null ||
                value === undefined
            ) {

                return '';

            }


            return String(value)
                .replace(
                    /&/g,
                    '&amp;'
                )
                .replace(
                    /</g,
                    '&lt;'
                )
                .replace(
                    />/g,
                    '&gt;'
                )
                .replace(
                    /"/g,
                    '&quot;'
                )
                .replace(
                    /'/g,
                    '&#039;'
                );
        }

        function togglePassword(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);

            if (input.type === 'password') {
                input.type = 'text';

                eye.innerHTML = `
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3.98 8.223A10.477 10.477 0 001.458 12C2.732 16.057 6.523 19 11 19c1.61 0 3.13-.36 4.49-1.003M6.228 6.228A10.45 10.45 0 0111 5c4.478 0 8.268 2.943 9.542 7a10.523 10.523 0 01-4.15 5.11M6.228 6.228L3 3m3.228 3.228l12.544 12.544"
            />
        `;
            } else {
                input.type = 'password';

                eye.innerHTML = `
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
            />
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
            />
        `;
            }
        }
    </script>

</x-app-layout>
