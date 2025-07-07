<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianPesertaResource\Pages;
use App\Models\NilaiTahapSeleksi;
use App\Models\TahapSeleksiPpdb;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

use App\Models\JumlahTagihanAwal;
use App\Models\TagihanSiswa;

class PenilaianPesertaResource extends Resource
{
    protected static ?string $model = NilaiTahapSeleksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Management PPDB';

    protected static ?string $navigationLabel = 'Penilaian Seleksi PPDB';

    protected static ?string $pluralModelLabel = 'Penilaian Seleksi PPDB';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('peserta_id')
                    ->relationship('peserta', 'nama_lengkap')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Nama Peserta')
                    ->disabled()
                    ->columnSpan(3),

                Select::make('tahap_seleksi_id')
                    ->relationship('tahapSeleksi', 'nama_tahap')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Tahap Seleksi')
                    ->disabled()
                    ->columnSpan(3),

                TextInput::make('nilai')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->label('Nilai')
                    ->columnSpan(3),

                Textarea::make('catatan')
                    ->maxLength(65535)
                    ->label('Catatan')
                    ->columnSpanFull()
                    ->columnSpan(3),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nama_lengkap')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tahapSeleksi.ppdb.judul_ppdb')
                    ->label('Gelombang PPDB')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tahapSeleksi.nama_tahap')
                    ->label('Tahap Seleksi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->numeric()
                    ->sortable()
                    ->summarize([
                        \Filament\Tables\Columns\Summarizers\Average::make('nilai')
                            ->label('Rata-Rata Nilai Semua Peserta')
                            ->formatStateUsing(fn(string $state): string => number_format($state, 2, ',', '.'))
                    ]),

                Tables\Columns\TextColumn::make('peserta_id')
                    ->label('Rata-Rata Nilai Peserta')
                    ->formatStateUsing(function ($state) {
                        static $sudahDitampilkan = [];

                        if (in_array($state, $sudahDitampilkan)) {
                            return ''; // baris duplikat → kosongkan
                        }

                        $sudahDitampilkan[] = $state;

                        $rataRata = \App\Models\NilaiTahapSeleksi::where('peserta_id', $state)->avg('nilai');

                        return $rataRata !== null
                            ? number_format($rataRata, 2, ',', '.')
                            : '-';
                    }),

            ])

            ->filters([
                //
                Tables\Filters\SelectFilter::make('tahap_seleksi_id')
                    ->label('Tahap Seleksi')
                    ->options(function () {
                        return TahapSeleksiPpdb::pluck('nama_tahap', 'id');
                    })
                    ->searchable(),

                Tables\Filters\SelectFilter::make('tahapSeleksi.ppdb_id')
                    ->label('Gelombang PPDB')
                    ->relationship('tahapSeleksi.ppdb', 'judul_ppdb')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Set Peserta Lulus')
                        ->label('Set Peserta Lulus')
                        ->action(function ($records) {
                            $pesertaIds = collect($records)
                                ->pluck('peserta_id')
                                ->filter()
                                ->unique()
                                ->values();

                            if ($pesertaIds->isNotEmpty()) {
                                foreach ($pesertaIds as $pesertaId) {
                                    $peserta = \App\Models\PesertaDidik::find($pesertaId);
                                    if ($peserta && $peserta->user) {
                                        $peserta->update(['status_ppdb' => 'Siswa Baru']);
                                        $peserta->user->assignRole('Siswa');
                                    }

                                    \App\Models\NilaiTahapSeleksi::where('peserta_id', $pesertaId)
                                        ->update(['status_lulus' => 'LULUS']);

                                    // Ambil data ppdb_id dan jurusan_id dari peserta untuk tagihan siswa
                                    $ppdbId = $peserta->ppdb_id;
                                    $jurusanId = $peserta->jurusan_id;

                                    // Ambil total tagihan awal dari tabel jumlah_tagihan_awal
                                    $jumlahTagihanAwal = \App\Models\JumlahTagihanAwal::where('ppdb_id', $ppdbId)
                                        ->where('jurusan_id', $jurusanId)
                                        ->first();

                                    if ($jumlahTagihanAwal) {
                                        $totalTagihan = $jumlahTagihanAwal->total_tagihan;
                                        $jumlahCicilan = $jumlahTagihanAwal->jumlah_cicilan;

                                        if ($jumlahCicilan > 0) {
                                            $nominalPerCicilan = (int) round($totalTagihan / $jumlahCicilan);

                                            // Loop insert cicilan ke tagihan_siswa
                                            for ($i = 1; $i <= $jumlahCicilan; $i++) {
                                                \App\Models\TagihanSiswa::create([
                                                    'peserta_didik_id' => $pesertaId,
                                                    'ppdb_id' => $ppdbId,
                                                    'jurusan_id' => $jurusanId,
                                                    'nominal' => $nominalPerCicilan,
                                                    'cicilan_ke' => $i,
                                                    'status_bayar' => 'belum', // status awal
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        })
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-o-check-circle'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenilaianPesertas::route('/'),
            'create' => Pages\CreatePenilaianPeserta::route('/create'),
            'edit' => Pages\EditPenilaianPeserta::route('/{record}/edit'),
            // 'rekap-nilai' => Pages\RekapNilaiPserta::route('/rekap-nilai'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Panitia Ppdb') || Auth::user()->hasRole('Admin') ||  Auth::user()->hasRole('Super Admin');;
    }

    public static function getTableQuery(): Builder
    {
        return \App\Models\NilaiTahapSeleksi::query()
            ->select('peserta_id')
            ->selectRaw('AVG(nilai) as rata_rata_nilai')
            ->groupBy('peserta_id');
    }
}
