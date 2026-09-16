<x-app-layout>
    <div class="p-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Backup Data
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Buat dan download backup database sistem rental.
                </p>
            </div>

            <button
                type="button"
                id="btnBackup"
                onclick="createBackup()"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span id="backupIcon">💾</span>
                <span id="backupText">Backup Sekarang</span>
            </button>
        </div>

        {{-- Alert --}}
        <div
            id="alertBox"
            class="hidden mb-6 px-4 py-3 rounded-lg text-sm font-medium"
        ></div>

        {{-- Info --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
            <div class="flex gap-3">
                <div class="text-xl">ℹ️</div>

                <div>
                    <h3 class="font-semibold text-blue-800">
                        Backup Database
                    </h3>

                    <p class="text-sm text-blue-700 mt-1">
                        Backup akan membuat file database dalam format
                        <strong>.sql</strong> dan menyimpannya di server.
                        File tersebut dapat didownload oleh Admin.
                    </p>
                </div>
            </div>
        </div>

        {{-- Backup List --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    Riwayat Backup
                </h2>
            </div>

            <div class="overflow-x-auto">

                @if($files->count())

                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                    No
                                </th>

                                <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                    Nama File
                                </th>

                                <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                    Ukuran
                                </th>

                                <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                    Dibuat
                                </th>

                                <th class="text-right px-6 py-4 font-semibold text-gray-600">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @foreach($files as $index => $file)

                                <tr class="hover:bg-gray-50 transition">

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xl">📄</span>

                                            <span class="font-medium text-gray-800">
                                                {{ $file['name'] }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ number_format($file['size'] / 1024, 2) }} KB
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $file['created_at'] }}
                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        <a
                                            href="{{ route('backups.download', $file['name']) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition"
                                        >
                                            ⬇️ Download
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>
                    </table>

                @else

                    <div class="py-16 text-center">

                        <div class="text-5xl mb-4">
                            💾
                        </div>

                        <h3 class="text-lg font-semibold text-gray-700">
                            Belum ada backup
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Klik tombol "Backup Sekarang" untuk membuat backup database.
                        </p>

                    </div>

                @endif

            </div>
        </div>

    </div>

    <script>
        async function createBackup() {

            const button = document.getElementById('btnBackup');
            const icon = document.getElementById('backupIcon');
            const text = document.getElementById('backupText');
            const alertBox = document.getElementById('alertBox');

            button.disabled = true;

            icon.textContent = '⏳';
            text.textContent = 'Membuat Backup...';

            alertBox.className = 'hidden';

            try {

                const response = await fetch("{{ route('backups.store') }}", {
                    method: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },

                    body: JSON.stringify({})
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(
                        result.message || 'Backup database gagal.'
                    );
                }

                alertBox.textContent =
                    '✅ ' + result.message + ' File: ' + result.filename;

                alertBox.className =
                    'mb-6 px-4 py-3 rounded-lg text-sm font-medium bg-green-50 border border-green-200 text-green-700';

                setTimeout(() => {
                    window.location.reload();
                }, 1000);

            } catch (error) {

                alertBox.textContent =
                    '❌ ' + error.message;

                alertBox.className =
                    'mb-6 px-4 py-3 rounded-lg text-sm font-medium bg-red-50 border border-red-200 text-red-700';

                button.disabled = false;

                icon.textContent = '💾';
                text.textContent = 'Backup Sekarang';
            }
        }
    </script>

</x-app-layout>