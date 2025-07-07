<x-filament::page>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Rekap Nilai Peserta</h2>
    </x-slot>

    <div class="w-full overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-md mt-4">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-800 dark:text-gray-300">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Peserta</th>
                    @foreach ($tahapList as $tahap)
                        <th scope="col" class="px-6 py-3 text-center">{{ $tahap }}</th>
                    @endforeach
                    <th scope="col" class="px-6 py-3 text-center">Rata-rata</th>
                </tr>
            </thead>
            <tbody id="table-body">
                @forelse ($rekapData as $peserta)
                    <tr
                        class="data-row bg-white border-b dark:bg-gray-900 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $peserta['nama'] }}
                        </td>
                        @foreach ($peserta['nilai_per_tahap'] as $nilai)
                            <td class="px-6 py-4 text-center">{{ $nilai }}</td>
                        @endforeach
                        <td class="px-6 py-4 text-center font-semibold text-primary-600 dark:text-primary-400">
                            {{ $peserta['rata_rata'] }}
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="{{ count($tahapList) + 2 }}"
                            class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data nilai peserta.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Rows per page selector --}}
    <div class="mt-4 flex items-center space-x-4">
        <label for="rowsPerPage" class="text-gray-700 dark:text-gray-300">Baris per halaman:</label>
        <select id="rowsPerPage" class="border rounded px-3 py-1 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
    </div>

    {{-- Pagination element for client-side --}}
    <div class="mt-4" id="pagination"></div>

    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tableBody = document.getElementById('table-body');
                const pagination = document.getElementById('pagination');
                const rowsPerPageSelect = document.getElementById('rowsPerPage');

                // Select only rows with data, exclude empty message row
                let rows = Array.from(tableBody.querySelectorAll('tr.data-row'));
                let currentPage = 1;
                let rowsPerPage = parseInt(rowsPerPageSelect.value);

                function renderTablePage(page) {
                    const start = (page - 1) * rowsPerPage;
                    const end = start + rowsPerPage;

                    rows.forEach((row, index) => {
                        row.style.display = (index >= start && index < end) ? '' : 'none';
                    });

                    // Jika kosong, tampilkan row empty
                    const emptyRow = tableBody.querySelector('tr.empty-row');
                    if (rows.length === 0) {
                        if (emptyRow) emptyRow.style.display = '';
                    } else {
                        if (emptyRow) emptyRow.style.display = 'none';
                    }
                }

                function renderPagination() {
                    const pageCount = Math.ceil(rows.length / rowsPerPage);
                    pagination.innerHTML = '';

                    // Jika data kosong, tampilkan tombol disabled page 1
                    if (rows.length === 0) {
                        const btn = document.createElement('button');
                        btn.textContent = '1';
                        btn.disabled = true;
                        btn.className = 'mx-1 px-3 py-1 border rounded bg-gray-300 text-gray-600 cursor-not-allowed';
                        pagination.appendChild(btn);
                        return;
                    }

                    for (let i = 1; i <= pageCount; i++) {
                        const btn = document.createElement('button');
                        btn.textContent = i;
                        btn.className = 'mx-1 px-3 py-1 border rounded cursor-pointer';
                        if (i === currentPage) btn.classList.add('bg-primary-600', 'text-white');

                        btn.addEventListener('click', () => {
                            currentPage = i;
                            renderTablePage(currentPage);
                            renderPagination();
                        });

                        pagination.appendChild(btn);
                    }
                }

                function update() {
                    // Update rows (in case data changes dynamically)
                    rows = Array.from(tableBody.querySelectorAll('tr.data-row'));
                    rowsPerPage = parseInt(rowsPerPageSelect.value);
                    currentPage = 1; // reset ke halaman 1 saat ubah rows per page
                    renderTablePage(currentPage);
                    renderPagination();
                }

                rowsPerPageSelect.addEventListener('change', update);

                // Initial render
                update();
            });
        </script>
    </x-slot>
</x-filament::page>
