<x-filament-panels::page>
    <x-filament::section>
        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab"
                data-tabs-toggle="#default-styled-tab-content"
                data-tabs-active-classes="text-yellow-500 hover:text-yellow-500 dark:text-yellow-500 dark:hover:text-yellow-500 border-yellow-500 dark:border-yellow-500"
                data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300"
                role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg transition duration-300 ease-in-out"
                        id="profile-styled-tab" data-tabs-target="#styled-profile" type="button" role="tab"
                        aria-controls="profile" aria-selected="true">Detail PPDB</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg transition duration-300 ease-in-out"
                        id="dashboard-styled-tab" data-tabs-target="#styled-dashboard" type="button" role="tab"
                        aria-controls="dashboard" aria-selected="false">Tahap Seleksi</button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg transition duration-300 ease-in-out"
                        id="settings-styled-tab" data-tabs-target="#styled-settings" type="button" role="tab"
                        aria-controls="settings" aria-selected="false">Grafik & Jumlah Pendaftar</button>
                </li>
                <li role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg transition duration-300 ease-in-out"
                        id="contacts-styled-tab" data-tabs-target="#styled-contacts" type="button" role="tab"
                        aria-controls="contacts" aria-selected="false">Nilai Siswa</button>
                </li>
            </ul>
        </div>

    </x-filament::section>
</x-filament-panels::page>
