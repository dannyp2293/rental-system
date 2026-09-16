<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-gray-900">
                Produk Rental
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Kelola semua produk yang tersedia untuk disewakan.
            </p>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">


            {{-- Alert --}}
            <div
                id="alert"
                class="mb-6 hidden rounded-lg px-4 py-3 text-sm font-medium"
            ></div>


            {{-- Main Card --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">


                {{-- Header --}}
                <div class="border-b border-gray-100 px-6 py-5">

                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                        <div>

                            <h3 class="font-bold text-gray-900">
                                Daftar Produk
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Total
                                <span id="productTotal">
                                    {{ $products->total() }}
                                </span>
                                produk
                            </p>

                        </div>


                        <button
                            type="button"
                            onclick="openCreateModal()"
                            class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700"
                        >
                            + Tambah Produk
                        </button>

                    </div>

                </div>


                {{-- Filters --}}
                <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-3">


                        {{-- Search --}}
                        <div class="relative md:col-span-1">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"
                                    />
                                </svg>

                            </div>

                            <input
                                type="text"
                                id="search"
                                placeholder="Cari nama atau kode produk..."
                                class="w-full rounded-xl border-gray-300 py-2.5 pl-10 pr-4 text-sm focus:border-gray-900 focus:ring-gray-900"
                            >

                        </div>


                        {{-- Category --}}
                        <select
                            id="categoryFilter"
                            class="rounded-xl border-gray-300 py-2.5 text-sm focus:border-gray-900 focus:ring-gray-900"
                        >

                            <option value="">
                                Semua Kategori
                            </option>

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>


                        {{-- Status --}}
                        <select
                            id="statusFilter"
                            class="rounded-xl border-gray-300 py-2.5 text-sm focus:border-gray-900 focus:ring-gray-900"
                        >

                            <option value="">
                                Semua Status
                            </option>

                            <option value="available">
                                Tersedia
                            </option>

                            <option value="rented">
                                Disewa
                            </option>

                            <option value="maintenance">
                                Maintenance
                            </option>

                        </select>

                    </div>

                </div>


                {{-- Table --}}
                <div id="productTable">

                    @include('products.partials.table', [
                        'products' => $products
                    ])

                </div>


                {{-- Pagination --}}
                <div
                    id="productPagination"
                    class="border-t border-gray-100 px-6 py-4"
                >

                    {{ $products->links() }}

                </div>

            </div>

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- MODAL --}}
    {{-- ====================================================== --}}

    <div
        id="productModal"
        class="fixed inset-0 z-50 hidden"
    >

        {{-- Overlay --}}
        <div
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
            onclick="closeModal()"
        ></div>


        {{-- Modal Container --}}
        <div class="relative flex min-h-screen items-center justify-center p-4">

            <div
                class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl"
                onclick="event.stopPropagation()"
            >


                {{-- Modal Header --}}
                <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-100 bg-white px-6 py-5">

                    <div>

                        <h3
                            id="modalTitle"
                            class="text-lg font-bold text-gray-900"
                        >
                            Tambah Produk
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Isi informasi produk rental.
                        </p>

                    </div>


                    <button
                        type="button"
                        onclick="closeModal()"
                        class="rounded-lg p-2 text-2xl leading-none text-gray-400 hover:bg-gray-100 hover:text-gray-700"
                    >
                        &times;
                    </button>

                </div>


                {{-- Form --}}
                <form
                    id="productForm"
                    enctype="multipart/form-data"
                    class="space-y-5 px-6 py-6"
                >

                    <input
                        type="hidden"
                        id="productId"
                    >


                    {{-- Image --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Foto Produk
                        </label>

                        <div class="flex items-center gap-4">

                            <div
                                id="imagePreview"
                                class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gray-100"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4 16l4-4 4 4 3-3 5 5M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>

                            </div>


                            <div>

                                <input
                                    type="file"
                                    id="image"
                                    name="image"
                                    accept="image/jpeg,image/png,image/webp"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-gray-700"
                                >

                                <p class="mt-1 text-xs text-gray-400">
                                    JPG, PNG atau WEBP. Maksimal 2 MB.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Code + Name --}}
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Kode Produk
                            </label>

                            <input
                                type="text"
                                id="code"
                                name="code"
                                placeholder="PRD-001"
                                class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                required
                            >

                            <p
                                id="codeError"
                                class="mt-1 hidden text-xs text-red-600"
                            ></p>

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Nama Produk
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                placeholder="Contoh: Kamera Sony"
                                class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                required
                            >

                            <p
                                id="nameError"
                                class="mt-1 hidden text-xs text-red-600"
                            ></p>

                        </div>

                    </div>


                    {{-- Category --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Kategori
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                            required
                        >

                            <option value="">
                                Pilih kategori
                            </option>

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>

                        <p
                            id="category_idError"
                            class="mt-1 hidden text-xs text-red-600"
                        ></p>

                    </div>


                    {{-- Prices --}}
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Harga per Jam
                            </label>

                            <div class="relative">

                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-400">
                                    Rp
                                </span>

                                <input
                                    type="number"
                                    id="price_per_hour"
                                    name="price_per_hour"
                                    min="0"
                                    placeholder="50000"
                                    class="w-full rounded-xl border-gray-300 pl-10 focus:border-gray-900 focus:ring-gray-900"
                                    required
                                >

                            </div>

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Harga per Hari
                            </label>

                            <div class="relative">

                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-400">
                                    Rp
                                </span>

                                <input
                                    type="number"
                                    id="price_per_day"
                                    name="price_per_day"
                                    min="0"
                                    placeholder="150000"
                                    class="w-full rounded-xl border-gray-300 pl-10 focus:border-gray-900 focus:ring-gray-900"
                                    required
                                >

                            </div>

                        </div>

                    </div>


                    {{-- Stock + Status --}}
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Stok
                            </label>

                            <input
                                type="number"
                                id="stock"
                                name="stock"
                                min="0"
                                placeholder="5"
                                class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                required
                            >

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                required
                            >

                                <option value="available">
                                    Tersedia
                                </option>

                                <option value="rented">
                                    Disewa
                                </option>

                                <option value="maintenance">
                                    Maintenance
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Description --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Deskripsi
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Deskripsi produk..."
                            class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                        ></textarea>

                    </div>


                    {{-- Buttons --}}
                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">

                        <button
                            type="button"
                            onclick="closeModal()"
                            class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            id="submitButton"
                            class="rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            Simpan Produk
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

        const modal = document.getElementById('productModal');

        const form = document.getElementById('productForm');

        let searchTimer;


        // ==========================================
        // ALERT
        // ==========================================

        function showAlert(message, type = 'success')
        {
            const alert = document.getElementById('alert');

            alert.innerText = message;

            alert.className =
                'mb-6 rounded-lg px-4 py-3 text-sm font-medium';

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
        // MODAL CREATE
        // ==========================================

        function openCreateModal()
        {
            editMode = false;

            form.reset();

            document.getElementById('productId').value = '';

            document.getElementById('modalTitle').innerText =
                'Tambah Produk';

            document.getElementById('submitButton').innerText =
                'Simpan Produk';

            clearErrors();

            resetImagePreview();

            modal.classList.remove('hidden');

            setTimeout(() => {
                document.getElementById('code').focus();
            }, 100);
        }


        // ==========================================
        // MODAL EDIT
        // ==========================================

        function openEditModal(product)
        {
            editMode = true;

            document.getElementById('productId').value =
                product.id;

            document.getElementById('modalTitle').innerText =
                'Edit Produk';

            document.getElementById('submitButton').innerText =
                'Update Produk';

            document.getElementById('code').value =
                product.code ?? '';

            document.getElementById('name').value =
                product.name ?? '';

            document.getElementById('category_id').value =
                product.category_id ?? '';

            document.getElementById('price_per_hour').value =
                product.price_per_hour ?? 0;

            document.getElementById('price_per_day').value =
                product.price_per_day ?? 0;

            document.getElementById('stock').value =
                product.stock ?? 0;

            document.getElementById('status').value =
                product.status ?? 'available';

            document.getElementById('description').value =
                product.description ?? '';

            document.getElementById('image').value = '';

            clearErrors();

            if (product.image) {

                document.getElementById('imagePreview').innerHTML = `
                    <img
                        src="/storage/${product.image}"
                        class="h-full w-full object-cover"
                    >
                `;

            } else {

                resetImagePreview();

            }

            modal.classList.remove('hidden');
        }


        // ==========================================
        // CLOSE MODAL
        // ==========================================

        function closeModal()
        {
            modal.classList.add('hidden');

            form.reset();

            clearErrors();

            resetImagePreview();
        }


        // ==========================================
        // IMAGE PREVIEW
        // ==========================================

        document.getElementById('image')
            .addEventListener('change', function(event)
            {
                const file = event.target.files[0];

                if (!file) {
                    resetImagePreview();
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e)
                {
                    document.getElementById('imagePreview').innerHTML = `
                        <img
                            src="${e.target.result}"
                            class="h-full w-full object-cover"
                        >
                    `;
                };

                reader.readAsDataURL(file);
            });


        function resetImagePreview()
        {
            document.getElementById('imagePreview').innerHTML = `
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-8 w-8 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 16l4-4 4 4 3-3 5 5M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                    />
                </svg>
            `;
        }


        // ==========================================
        // ERRORS
        // ==========================================

        function clearErrors()
        {
            document.querySelectorAll('[id$="Error"]').forEach(
                element => {
                    element.innerText = '';
                    element.classList.add('hidden');
                }
            );
        }


        function showValidationErrors(errors)
        {
            clearErrors();

            Object.keys(errors).forEach(field => {

                const element = document.getElementById(
                    `${field}Error`
                );

                if (element) {

                    element.innerText =
                        errors[field][0];

                    element.classList.remove('hidden');

                }

            });
        }


        // ==========================================
        // SUBMIT
        // ==========================================

        form.addEventListener('submit', async function(e)
        {
            e.preventDefault();

            clearErrors();

            const id =
                document.getElementById('productId').value;

            const url = editMode
                ? `/products/${id}`
                : '/products';

            const data = new FormData(form);

            if (editMode) {
                data.append('_method', 'PUT');
            }

            const button =
                document.getElementById('submitButton');

            button.disabled = true;

            button.innerText =
                editMode
                    ? 'Mengupdate...'
                    : 'Menyimpan...';


            try {

                const response = await fetch(url, {

                    method: 'POST',

                    headers: {
                        'Accept': 'application/json',

                        'X-CSRF-TOKEN':
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .getAttribute('content')
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
                    result.message,
                    'success'
                );

                loadProducts();


            } catch(error) {

                console.error(error);

                showAlert(
                    error.message,
                    'error'
                );

            } finally {

                button.disabled = false;

                button.innerText =
                    editMode
                        ? 'Update Produk'
                        : 'Simpan Produk';

            }

        });


        // ==========================================
        // DELETE
        // ==========================================

        async function deleteProduct(id)
        {
            if (
                !confirm(
                    'Yakin ingin menghapus produk ini?'
                )
            ) {
                return;
            }


            try {

                const response = await fetch(
                    `/products/${id}`,
                    {

                        method: 'DELETE',

                        headers: {
                            'Accept': 'application/json',

                            'X-CSRF-TOKEN':
                                document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .getAttribute('content')
                        }

                    }
                );


                const result =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        result.message ??
                        'Gagal menghapus produk.'
                    );

                }


                showAlert(
                    result.message,
                    'success'
                );


                loadProducts();


            } catch(error) {

                console.error(error);

                showAlert(
                    error.message,
                    'error'
                );

            }
        }


        // ==========================================
        // LOAD PRODUCTS
        // ==========================================

        async function loadProducts(page = 1)
        {
            const search =
                document.getElementById('search').value;

            const category =
                document.getElementById('categoryFilter').value;

            const status =
                document.getElementById('statusFilter').value;


            const params = new URLSearchParams();

            params.append('page', page);

            if (search) {
                params.append('search', search);
            }

            if (category) {
                params.append('category_id', category);
            }

            if (status) {
                params.append('status', status);
            }


            const table =
                document.getElementById('productTable');

            table.classList.add('opacity-50');


            try {

                const response = await fetch(
                    `/products?${params.toString()}`,
                    {
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
                        'Gagal mengambil data produk.'
                    );

                }


                table.innerHTML =
                    result.html;

                document.getElementById(
                    'productPagination'
                ).innerHTML =
                    result.pagination;

                document.getElementById(
                    'productTotal'
                ).innerText =
                    result.total;


            } catch(error) {

                console.error(error);

                showAlert(
                    error.message,
                    'error'
                );

            } finally {

                table.classList.remove('opacity-50');

            }
        }


        // ==========================================
        // SEARCH
        // ==========================================

        document.getElementById('search')
            .addEventListener('input', function()
            {
                clearTimeout(searchTimer);

                searchTimer = setTimeout(() => {

                    loadProducts();

                }, 400);
            });


        // ==========================================
        // FILTER
        // ==========================================

        document.getElementById('categoryFilter')
            .addEventListener('change', function()
            {
                loadProducts();
            });


        document.getElementById('statusFilter')
            .addEventListener('change', function()
            {
                loadProducts();
            });


        // ==========================================
        // AJAX PAGINATION
        // ==========================================

        document
            .getElementById('productPagination')
            .addEventListener(
                'click',
                function(e)
                {
                    const link =
                        e.target.closest('a');

                    if (!link) {
                        return;
                    }

                    e.preventDefault();

                    const url =
                        new URL(link.href);

                    const page =
                        url.searchParams.get('page') ?? 1;

                    loadProducts(page);
                }
            );


        // ==========================================
        // ESC
        // ==========================================

        document.addEventListener(
            'keydown',
            function(e)
            {
                if (e.key === 'Escape') {
                    closeModal();
                }
            }
        );

    </script>

</x-app-layout>