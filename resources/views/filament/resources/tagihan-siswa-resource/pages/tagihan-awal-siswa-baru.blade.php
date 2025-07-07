    <x-filament::page>
        <div
            class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 p-6 shadow-sm font-mono text-sm text-gray-800 dark:text-gray-100">
            {{-- Header Resi --}}
            <div class="text-center mb-4">
                <h2 class="text-xl font-bold tracking-wide">DETAIL PEMBAYARAN AWAL</h2>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Cicilan Ke - {{ $detailTagihan?->cicilan_ke }}</p>
            </div>

            <hr class="border-dashed border-t border-gray-400 dark:border-gray-600 mb-4" />

            {{-- Detail Resi --}}
            <table class="w-fit mb-4">
                <tbody>
                    <tr>
                        <td class="py-1 font-semibold w-1/2">Nomor Registrasi</td>
                        <td class="py-1">: {{ $this->nomorRegistrasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-semibold">Nama Peserta Didik</td>
                        <td class="py-1">: {{ $this->namaPesertaDidik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-semibold">Nomor Induk Siswa Nasional</td>
                        <td class="py-1">: {{ $this->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-semibold">Jurusan </td>
                        <td class="py-1">: {{ $this->jurusanPeserta ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td class="py-1 font-semibold">Jalur Daftar Peserta </td>
                        <td class="py-1">: {{ $this->jalurPeserta ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-semibold">Status Pembayaran</td>
                        <td class="py-1" id="status-pembayaran">:
                            {{ $this->statusPeserta === 'Siswa Baru' ? 'Menunggu Pembayaran' : 'Terverifikasi' }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr class="border-dashed border-t border-gray-400 dark:border-gray-600 mb-4" />


            {{-- Data Pembayaran --}}
            @foreach ($dataPeserta as $item)
                <div class="flex justify-between items-center mb-2">
                    <span>{{ $item->nama_pembayaran ?? '-' }}</span>
                    <span class="text-green-700 dark:text-green-400">Rp
                        {{ number_format($item->nominal ?? 0, 0, ',', '.') }}</span>
                </div>
            @endforeach

            <hr class="border-dashed border-t border-gray-400 dark:border-gray-600 mb-4" />

            @foreach ($dataTagihan as $tagihan)
                <div class="flex justify-between items-center mb-2 p-3 border rounded-md bg-gray-50 dark:bg-gray-800">
                    <div>
                        <div class="text-green-700 dark:text-green-400 font-semibold">
                            Cicilan Ke-{{ $loop->iteration }} {{-- atau gunakan $tagihan->cicilan_ke jika ada --}}
                        </div>
                        <div>
                            @if ($tagihan->status_bayar === 'belum')
                                <span class="text-xs px-2 py-1 rounded-full text-green-700 dark:text-green-400 font-semibold">Belum Lunas</span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full text-green-700 dark:text-green-400 font-semibold">Lunas</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-green-700 dark:text-green-400 font-semibold">
                        Rp {{ number_format($tagihan->nominal ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach


            <hr class="border-dashed border-t border-gray-400 dark:border-gray-600 mb-4" />

            {{-- Total Biaya --}}
            <div class="flex justify-between items-center text-lg font-bold mb-6">
                <span>Total Biaya Pembayara Awal</span>
                <span class="text-green-700 dark:text-green-400">Rp
                    {{ number_format($this->totalBiaya ?? 0, 0, ',', '.') }}</span>
            </div>

            <br>

            @if ($statusBayarCicilan === 'belum')
                <div class="text-center" id="payment-button-container">
                    <x-filament::button id="pay-button" color="warning" class="px-6 py-2 text-white text-sm">
                        Lanjutkan Pembayaran
                    </x-filament::button>
                </div>
            @endif

            {{-- Footer --}}
            <div class="text-xs text-gray-400 dark:text-gray-500 mt-6 text-center">
                * Resi ini adalah bentuk tagihan dan bukti pembayaran yang sah.
            </div>
        </div>

        {{-- Midtrans Script --}}
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
        </script>

        <script type="text/javascript">
            document.getElementById('pay-button').addEventListener('click', function() {
                snap.pay('{{ $snapToken ?? '' }}', {
                    onSuccess: function(result) {
                        console.log('Payment Success:', result);

                        fetch('/pembayaran/awal/success', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    peserta_didik_id: {{ $pesertaDidikId }},
                                    ppdb_id: {{ $ppdbId }},
                                    nomor_transaksi: result.transaction_id,
                                    tanggal_bayar: result.transaction_time,
                                    metode_pembayaran: result.payment_type,
                                    jumlah: result.gross_amount,
                                    biaya_sekolah: {{ $totalBiaya }},
                                    biaya_mitrans: {{ $biayaMitrans }},
                                    biaya_pengembangan: {{ $biayaPengembangan }},
                                    nama: {!! json_encode($namaPesertaDidik) !!},
                                    nisn: {{ $nisn }},
                                    record_id: {{ $recordId }},
                                    deskripsi: 'Pembayaran Awal Masuk Sekolah untuk Peserta Didik Baru atas Nama ' + {!! json_encode($namaPesertaDidik) !!} + ', NISN ' + {{ $nisn }} +' dan Cicilan ke ' + {{ $detailTagihan?->cicilan_ke }},
                                    status: 'Pembayaran Sukses',
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Sembunyikan tombol setelah sukses
                                const buttonContainer = document.getElementById(
                                    'payment-button-container');
                                if (buttonContainer) {
                                    buttonContainer.remove(); // hapus elemen tombol
                                }                                

                                // refresh data 
                                window.location.reload();
                            });
                    },
                    onPending: function(result) {
                        console.log('Payment Pending:', result);
                    },
                    onError: function(result) {
                        console.log('Payment Error:', result);
                    },
                    onClose: function() {
                        console.log('Popup closed without finishing the payment');
                    }
                });
            });
        </script>
    </x-filament::page>
