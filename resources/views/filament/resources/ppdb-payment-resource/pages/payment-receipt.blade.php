    <x-filament::page>
        <div
            class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 p-6 shadow-sm font-mono text-sm text-gray-800 dark:text-gray-100">
            {{-- Header Resi --}}
            <div class="text-center mb-4">
                <h2 class="text-xl font-bold tracking-wide">DETAIL PEMBAYARAN PPDB</h2>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Penerimaan Peserta Didik Baru</p>
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
                        <td class="py-1 font-semibold">Judul PPDB</td>
                        <td class="py-1">: {{ $this->judulPPDB ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-semibold">Status Pembayaran</td>
                        <td class="py-1" id="status-pembayaran">:
                            {{ $this->statusPpdb === 'Peserta Baru' ? 'Menunggu Pembayaran' : 'Terverifikasi' }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr class="border-dashed border-t border-gray-400 dark:border-gray-600 mb-4" />

            {{-- Total Biaya --}}
            <div class="flex justify-between items-center text-lg font-bold mb-6">
                <span>Total Biaya PPDB</span>
                <span class="text-green-700 dark:text-green-400">Rp
                    {{ number_format($this->totalBiaya ?? 0, 0, ',', '.') }}</span>
            </div>

            <br>

            @if ($statusPpdb === 'Peserta Baru') 
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
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

        <script type="text/javascript">
            document.getElementById('pay-button').addEventListener('click', function() {
                snap.pay('{{ $snapToken ?? '' }}', {
                    onSuccess: function(result) {
                        console.log('Payment Success:', result);

                        fetch('/payment/ppdb/success', {
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
                                    biaya_sekolah: {{ $biayaSekolah }},
                                    biaya_mitrans: {{ $biayaMitrans }},
                                    biaya_pengembangan: {{ $biayaPengembangan }},
                                    bukti_pembayaran: null,
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

                                // Opsional: update teks status pembayaran di halaman
                                const statusText = document.getElementById('status-pembayaran');
                                if (statusText) {
                                    statusText.textContent = ': Terverifikasi';
                                }
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
