<x-app-layout>

    {{-- <x-slot name="header">
 <div class="flex items-center w-full">
    <div>
        <h2 class="text-xl font-bold text-gray-800">
            Kategori Produk
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Kelola kategori produk rental.
        </p>
    </div>

    <button
        type="button"
        onclick="openCreateModal()"
        class="ml-auto rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700"
    >
        + Tambah Kategori
    </button>
</div>
    </x-slot> --}}

    <div class="py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Alert --}}
            <div
                id="alert"
                class="mb-6 hidden rounded-lg px-4 py-3 text-sm font-medium"
            ></div>

            {{-- Card --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm">

    {{-- CARD HEADER --}}
    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

        <div>
            <h3 class="text-lg font-bold text-gray-800">
                Daftar Kategori
            </h3>

           
            <p class="mt-1 text-sm text-gray-500">
                Total {{ $categories->total() }} kategori
            </p>
        </div>

        <button
            type="button"
            onclick="openCreateModal()"
            class="rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700"
        >
            + Tambah Kategori
        </button>

    </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                    #
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                    Nama
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                    Deskripsi
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-gray-500">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody
                            id="categoryTable"
                            class="divide-y divide-gray-100"
                        >

                            @forelse($categories as $category)

                                <tr
                                    id="category-row-{{ $category->id }}"
                                    class="transition hover:bg-gray-50"
                                >

                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $categories->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-6 py-4">

                                        <div class="font-semibold text-gray-900">
                                            {{ $category->name }}
                                        </div>

                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $category->description ?: '-' }}
                                    </td>

                                    <td class="px-6 py-4">

                                        @if($category->is_active)

                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Aktif
                                            </span>

                                        @else

                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                Nonaktif
                                            </span>

                                        @endif

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        <button
                                            type="button"
                                            onclick='openEditModal(@json($category))'
                                            class="mr-3 text-sm font-semibold text-blue-600 hover:text-blue-800"
                                        >
                                            Edit
                                        </button>

                                        <button
                                            type="button"
                                            onclick="deleteCategory({{ $category->id }})"
                                            class="text-sm font-semibold text-red-600 hover:text-red-800"
                                        >
                                            Hapus
                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr id="empty-row">

                                    <td
                                        colspan="5"
                                        class="px-6 py-12 text-center"
                                    >

                                        <div class="text-sm text-gray-500">
                                            Belum ada kategori.
                                        </div>

                                        <button
                                            onclick="openCreateModal()"
                                            class="mt-2 text-sm font-semibold text-gray-900 hover:underline"
                                        >
                                            + Tambah kategori pertama
                                        </button>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if($categories->hasPages())

                    <div class="border-t border-gray-100 px-6 py-4">
                        {{ $categories->links() }}
                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- ============================= --}}
    {{-- MODAL --}}
    {{-- ============================= --}}

    <div
        id="categoryModal"
        class="fixed inset-0 z-50 hidden"
    >

        {{-- Overlay --}}
        <div
            class="absolute inset-0 bg-black/50"
            onclick="closeModal()"
        ></div>

        {{-- Modal --}}
        <div class="relative flex min-h-screen items-center justify-center p-4">

            <div
                class="w-full max-w-lg rounded-2xl bg-white shadow-xl"
                onclick="event.stopPropagation()"
            >

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5">

                    <div>

                        <h3
                            id="modalTitle"
                            class="text-lg font-bold text-gray-900"
                        >
                            Tambah Kategori
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Isi informasi kategori produk.
                        </p>

                    </div>

                    <button
                        type="button"
                        onclick="closeModal()"
                        class="text-2xl text-gray-400 hover:text-gray-700"
                    >
                        &times;
                    </button>

                </div>


                {{-- Form --}}
                <form
                    id="categoryForm"
                    class="space-y-5 px-6 py-6"
                >

                    <input
                        type="hidden"
                        id="categoryId"
                    >

                    {{-- Nama --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Nama Kategori
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Contoh: Kamera"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                            required
                        >

                        <p
                            id="nameError"
                            class="mt-1 hidden text-sm text-red-600"
                        ></p>

                    </div>


                    {{-- Deskripsi --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Deskripsi
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Deskripsi kategori..."
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                        ></textarea>

                    </div>


                    {{-- Status --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Status
                        </label>

                        <select
                            id="is_active"
                            name="is_active"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                        >

                            <option value="1">
                                Aktif
                            </option>

                            <option value="0">
                                Nonaktif
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">

                        <button
                            type="button"
                            onclick="closeModal()"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            id="submitButton"
                            class="rounded-lg bg-gray-900 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-700"
                        >
                            Simpan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- ============================= --}}
    {{-- AJAX --}}
    {{-- ============================= --}}

    <script>

        let editMode = false;

        const modal = document.getElementById('categoryModal');
        const form = document.getElementById('categoryForm');

        function openCreateModal()
        {
            editMode = false;

            document.getElementById('modalTitle').innerText = 'Tambah Kategori';
            document.getElementById('submitButton').innerText = 'Simpan';

            document.getElementById('categoryId').value = '';
            document.getElementById('name').value = '';
            document.getElementById('description').value = '';
            document.getElementById('is_active').value = '1';

            clearErrors();

            modal.classList.remove('hidden');

            setTimeout(() => {
                document.getElementById('name').focus();
            }, 100);
        }


        function openEditModal(category)
        {
            editMode = true;

            document.getElementById('modalTitle').innerText = 'Edit Kategori';
            document.getElementById('submitButton').innerText = 'Update';

            document.getElementById('categoryId').value = category.id;
            document.getElementById('name').value = category.name;
            document.getElementById('description').value = category.description ?? '';
            document.getElementById('is_active').value = category.is_active ? '1' : '0';

            clearErrors();

            modal.classList.remove('hidden');

            setTimeout(() => {
                document.getElementById('name').focus();
            }, 100);
        }


        function closeModal()
        {
            modal.classList.add('hidden');

            form.reset();

            clearErrors();
        }


        function clearErrors()
        {
            document.getElementById('nameError').classList.add('hidden');
            document.getElementById('nameError').innerText = '';
        }


        function showAlert(message, type = 'success')
        {
            const alert = document.getElementById('alert');

            alert.innerText = message;

            alert.classList.remove(
                'hidden',
                'bg-green-50',
                'text-green-700',
                'bg-red-50',
                'text-red-700'
            );

            if(type === 'success') {

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
            }, 3000);
        }


        form.addEventListener('submit', async function(e)
        {
            e.preventDefault();

            clearErrors();

            const id = document.getElementById('categoryId').value;

            const url = editMode
                ? `/categories/${id}`
                : `/categories`;

            const method = editMode
                ? 'PUT'
                : 'POST';

            const data = {
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                is_active: document.getElementById('is_active').value
            };

            const button = document.getElementById('submitButton');

            button.disabled = true;
            button.innerText = 'Menyimpan...';

            try {

                const response = await fetch(url, {

                    method: method,

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },

                    body: JSON.stringify(data)

                });

                const result = await response.json();

                if(!response.ok) {

                    if(result.errors?.name) {

                        const error = document.getElementById('nameError');

                        error.innerText = result.errors.name[0];

                        error.classList.remove('hidden');

                    }

                    throw new Error(
                        result.message ?? 'Terjadi kesalahan.'
                    );

                }

                closeModal();

                showAlert(
                    result.message,
                    'success'
                );

                setTimeout(() => {
                    window.location.reload();
                }, 500);

            } catch(error) {

                console.error(error);

                showAlert(
                    error.message,
                    'error'
                );

            } finally {

                button.disabled = false;

                button.innerText = editMode
                    ? 'Update'
                    : 'Simpan';

            }

        });


        async function deleteCategory(id)
        {
            if(!confirm('Yakin ingin menghapus kategori ini?')) {
                return;
            }

            try {

                const response = await fetch(
                    `/categories/${id}`,
                    {

                        method: 'DELETE',

                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }

                    }
                );

                const result = await response.json();

                if(!response.ok) {
                    throw new Error(
                        result.message ?? 'Gagal menghapus kategori.'
                    );
                }

                const row = document.getElementById(
                    `category-row-${id}`
                );

                if(row) {
                    row.remove();
                }

                showAlert(
                    result.message,
                    'success'
                );

            } catch(error) {

                console.error(error);

                showAlert(
                    error.message,
                    'error'
                );

            }
        }


        // ESC untuk tutup modal
        document.addEventListener('keydown', function(e)
        {
            if(e.key === 'Escape') {
                closeModal();
            }
        });

    </script>

</x-app-layout>