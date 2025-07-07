<x-filament::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- BIO + FOTO --}}
        <div
            class="relative overflow-hidden rounded-2xl shadow-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">

            <!-- Konten Profil -->
            <div class="relative z-10 p-6 flex flex-col items-center text-center">
                <img src="{{ $peserta->foto ?? 'https://ui-avatars.com/api/?name=' . urlencode($peserta->nama_lengkap) }}"
                    alt="Foto {{ $peserta->nama_lengkap }}"
                    class="w-60 h-80 rounded-xl object-cover border border-primary-500 shadow" />
                <br>
                <div class="mt-4 space-y-1">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $peserta->nama_lengkap }}</h2>
                    <p class="text-sm text-gray-800 dark:text-gray-300">NISN: {{ $peserta->nisn }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-300">NIK: {{ $peserta->nik ?? '-' }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-300">Nomor Registrasi:
                        {{ $peserta->nomor_registrasi ?? '-' }}</p>
                    <br>
                    <div class="text-center mt-2">
                        <a href="{{ route('filament.lms.resources.peserta-didiks.edit', $peserta->id) }}"
                            class="inline-block px-2 py-1 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition text-sm">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>


        {{-- DETAIL PRIBADI --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-primary-600 mb-4 border-b pb-2">Informasi Pribadi</h3>
            <dl class="space-y-3 text-sm text-gray-800 dark:text-white">
                <div class="flex justify-between">
                    <dt class="text-gray-800 dark:text-gray-300">Tempat Lahir</dt>
                    <dd class="font-medium">{{ $peserta->tempat_lahir ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-800 dark:text-gray-300">Tanggal Lahir</dt>
                    <dd class="font-medium">{{ $peserta->tanggal_lahir ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-800 dark:text-gray-300">Jenis Kelamin</dt>
                    <dd class="font-medium">{{ $peserta->jenis_kelamin ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-800 dark:text-gray-300">Agama</dt>
                    <dd class="font-medium">{{ $peserta->agama ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-800 dark:text-gray-300">Alamat</dt>
                    <dd class="font-medium text-right">{{ $peserta->alamat_lengkap ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-800 dark:text-gray-300">Asal Sekolah</dt>
                    <dd class="font-medium">{{ $peserta->asal_sekolah ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        {{-- DOKUMEN --}}
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-md border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-primary-600 mb-4 border-b pb-2">Dokumen</h3>
            <dl class="space-y-6 text-sm text-gray-800 dark:text-white">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- File Ijazah --}}
                    <div x-data="{ isOpen: false }">
                        <dt class="text-gray-800 dark:text-gray-300 mb-1 font-medium">IJAZAH</dt>
                        <dd>
                            @if ($peserta->file_ijazah)
                                @php
                                    $fileIjazahUrl = Storage::url($peserta->file_ijazah);
                                    $fileExtension = pathinfo($peserta->file_ijazah, PATHINFO_EXTENSION);
                                @endphp
                                @if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img @click="isOpen = true" src="{{ $fileIjazahUrl }}" alt="File Ijazah"
                                        class="w-full h-40 object-cover rounded-lg mb-2 cursor-pointer hover:opacity-80" />
                                @elseif (strtolower($fileExtension) === 'pdf')
                                    <embed src="{{ $fileIjazahUrl }}" type="application/pdf"
                                        class="w-full h-40 mb-2 rounded-lg" />
                                @else
                                    <p class="text-gray-400">Pratinjau tidak tersedia.</p>
                                @endif
                                <a href="{{ $fileIjazahUrl }}" target="_blank"
                                    class="text-primary-600 hover:underline">Lihat Dokumen</a>
                            @else
                                <span class="text-gray-400">Belum diunggah</span>
                            @endif
                        </dd>

                        {{-- Modal Preview Ijazah --}}
                        <div x-show="isOpen" x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
                            <div @click="isOpen = false" class="absolute inset-0 cursor-pointer"></div>
                            <div class="bg-white dark:bg-gray-900 p-4 rounded-xl relative">
                                <button @click="isOpen = false"
                                    class="absolute top-4 right-4 text-white text-xl">×</button>
                                <img src="{{ $fileIjazahUrl }}" alt="File Ijazah"
                                    class="w-full h-auto rounded-lg" />
                            </div>
                        </div>
                    </div>

                    {{-- File SKHUN (KTP?) --}}
                    <div x-data="{ isOpen: false }">
                        <dt class="text-gray-800 dark:text-gray-300 mb-1 font-medium">SKHUN / KTP</dt>
                        <dd>
                            @if ($peserta->file_ktp)
                                @php
                                    $fileKtpUrl = Storage::url($peserta->file_ktp);
                                    $fileExtension = pathinfo($peserta->file_ktp, PATHINFO_EXTENSION);
                                @endphp
                                @if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img @click="isOpen = true" src="{{ $fileKtpUrl }}" alt="File KTP"
                                        class="w-full h-40 object-cover rounded-lg mb-2 cursor-pointer hover:opacity-80" />
                                @elseif (strtolower($fileExtension) === 'pdf')
                                    <embed src="{{ $fileKtpUrl }}" type="application/pdf"
                                        class="w-full h-40 mb-2 rounded-lg" />
                                @else
                                    <p class="text-gray-400">Pratinjau tidak tersedia.</p>
                                @endif
                                <a href="{{ $fileKtpUrl }}" target="_blank"
                                    class="text-primary-600 hover:underline">Lihat Dokumen</a>
                            @else
                                <span class="text-gray-400">Belum diunggah</span>
                            @endif
                        </dd>

                        {{-- Modal Preview KTP --}}
                        <div x-show="isOpen" x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
                            <div @click="isOpen = false" class="absolute inset-0 cursor-pointer"></div>
                            <div class="bg-white dark:bg-gray-900 p-4 rounded-xl relative">
                                <button @click="isOpen = false"
                                    class="absolute top-4 right-4 text-white text-xl">×</button>
                                <img src="{{ $fileKtpUrl }}" alt="File KTP" class="w-full h-auto rounded-lg" />
                            </div>
                        </div>
                    </div>

                </div>
            </dl>
        </div>
    </div>


</x-filament::page>
