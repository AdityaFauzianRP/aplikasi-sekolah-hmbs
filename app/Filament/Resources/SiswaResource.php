<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\PesertaDidik;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class SiswaResource extends Resource
{
    protected static ?string $model = PesertaDidik::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Manajemen User';
    protected static ?string $navigationLabel = 'Data Siswa';
    protected static ?string $label = 'Data Siswa';
    protected static ?string $pluralLabel = 'Data Siswa';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('gelombang_ppdb_id')
                ->label('Gelombang PPDB')
                ->required()
                ->relationship('ppdb', 'judul_ppdb')
                ->searchable()
                ->preload()
                ->reactive()
                ->disabled(fn(string $context) => $context === 'edit'),

            Select::make('jurusan_id')
                ->label('Jurusan')
                ->required()
                ->relationship('jurusan', 'nama')
                ->searchable()
                ->preload()
                ->disabled(fn(string $context) => $context === 'edit'),

            Tabs::make('Formulir PPDB')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Data Peserta')
                        ->schema([
                            TextInput::make('nama_lengkap')
                                ->required()
                                ->label('Nama Lengkap Peserta Didik')
                                ->maxLength(255),

                            TextInput::make('nisn')
                                ->required()
                                ->label('NISN')
                                ->maxLength(16),

                            TextInput::make('nik')
                                ->required()
                                ->label('NIK')
                                ->maxLength(16),

                            TextInput::make('tempat_lahir')
                                ->required()
                                ->label('Tempat Lahir Peserta Didik')
                                ->maxLength(255),

                            DatePicker::make('tanggal_lahir')
                                ->required()
                                ->label('Tanggal Lahir'),

                            TextInput::make('jenis_kelamin')
                                ->required()
                                ->label('Jenis Kelamin'),

                            TextInput::make('asal_sekolah')
                                ->required()
                                ->label('Asal Sekolah'),

                            Select::make('agama')
                                ->required()
                                ->label('Agama')
                                ->options([
                                    'Islam' => 'Islam',
                                    'Kristen' => 'Kristen',
                                    'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu',
                                    'Buddha' => 'Buddha',
                                    'Konghucu' => 'Konghucu',
                                    'Lainnya' => 'Lainnya',
                                ])
                                ->searchable()
                                ->preload(),

                            TextInput::make('alamat_lengkap')
                                ->required()
                                ->label('Alamat Lengkap Peserta Didik')
                                ->maxLength(255),

                            Select::make('provinsi')
                                ->label('Provinsi')
                                ->options(function () {
                                    $response = Http::get('https://wilayah.id/api/provinces.json');

                                    if ($response->successful()) {
                                        return collect($response->json('data'))->pluck('name', 'code')->toArray();
                                    }

                                    return [];
                                })
                                ->required()
                                ->searchable()
                                ->preload()
                                ->reactive(),

                            Select::make('kabupaten')
                                ->label('Kabupaten/Kota')
                                ->options(function (callable $get) {
                                    $provinsiCode = $get('provinsi');

                                    if (!$provinsiCode) {
                                        return [];
                                    }

                                    $response = Http::get("https://wilayah.id/api/regencies/{$provinsiCode}.json");

                                    if ($response->successful()) {
                                        return collect($response->json('data'))->pluck('name', 'code')->toArray();
                                    }

                                    return [];
                                })
                                ->required()
                                ->searchable()
                                ->preload()
                                ->reactive()
                                ->disabled(fn(callable $get) => !$get('provinsi')),

                            Select::make('kecamatan')
                                ->label('Kecamatan')
                                ->options(function (callable $get) {
                                    $kabupatenCode = $get('kabupaten');

                                    if (!$kabupatenCode) {
                                        return [];
                                    }

                                    $response = Http::get("https://wilayah.id/api/districts/{$kabupatenCode}.json");

                                    if ($response->successful()) {
                                        return collect($response->json('data'))->pluck('name', 'code')->toArray();
                                    }

                                    return [];
                                })
                                ->required()
                                ->searchable()
                                ->preload()
                                ->reactive()
                                ->disabled(fn(callable $get) => !$get('kabupaten')),

                            Select::make('kelurahan')
                                ->label('Kelurahan/Desa')
                                ->options(function (callable $get) {
                                    $kecamatanCode = $get('kecamatan');

                                    if (!$kecamatanCode) {
                                        return [];
                                    }

                                    $response = Http::get("https://wilayah.id/api/villages/{$kecamatanCode}.json");

                                    if ($response->successful()) {
                                        return collect($response->json('data'))->pluck('name', 'code')->toArray();
                                    }

                                    return [];
                                })
                                ->required()
                                ->searchable()
                                ->preload()
                                ->disabled(fn(callable $get) => !$get('kecamatan')),

                            TextInput::make('kode_pos')
                                ->label('Kode Pos')
                                ->required()
                                ->label('Kode Pos Peserta Didik')
                                ->maxLength(10),

                            TextInput::make('no_hp')
                                ->label('No. HP Peserta Didik')
                                ->required()
                                ->maxLength(15),

                            TextInput::make('email')
                                ->label('Email Peserta Didik')
                                ->required()
                                ->maxLength(255)
                                ->email()
                                ->disabled(),

                            TextInput::make('hobi')
                                ->label('Hobi Peserta Didik')
                                ->maxLength(255),

                            TextInput::make('cita_cita')
                                ->label('Cita-cita Peserta Didik')
                                ->maxLength(255),
                        ])
                        ->columns(2),

                    Tabs\Tab::make('Data Tambahan')
                        ->schema([
                            // Tambahkan komponen form Data Tambahan
                            TextInput::make('anak_ke')
                                ->label('Anak Ke-')
                                ->required()
                                ->numeric(),

                            TextInput::make('jumlah_saudara_kandung')
                                ->label('Jumlah Saudara Kandung')
                                ->required()
                                ->numeric(),

                            TextInput::make('jumlah_saudara_tiri')
                                ->label('Jumlah Saudara Tiri')
                                ->required()
                                ->numeric(),

                            TextInput::make('jumlah_saudara_angkat')
                                ->label('Jumlah Saudara Angkat')
                                ->required()
                                ->numeric(),

                            TextInput::make('status_tempat_tinggal')
                                ->label('Status Tempat Tinggal')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: Tinggal Bersama Orang Tua, Asrama, Kost, dll'),

                            TextInput::make('jarak_rumah_km')
                                ->label('Jarak Tempat Tinggal ke Sekolah')
                                ->required()
                                ->numeric()
                                ->placeholder('Dalam KM'),

                            TextInput::make('alat_transportasi')
                                ->label('Alat Transportasi ke Sekolah')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: Sepeda, Motor, Mobil, Jalan Kaki, dll'),

                            TextInput::make('waktu_tempuh_menit')
                                ->label('Waktu Tempuh ke Sekolah')
                                ->required()
                                ->numeric()
                                ->placeholder('Dalam Menit'),

                            TextInput::make('info_sekolah_dari')
                                ->label('Informasi Sekolah Dari')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: Teman, Media Sosial, Brosur, dll'),

                            TextInput::make('asal_sekolah')
                                ->label('Asal Sekolah')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: SDN 1 Jakarta, SMPN 2 Bandung, dll'),

                            TextInput::make('alamat_asal_sekolah')
                                ->label('Alamat Asal Sekolah')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: Jl. Raya No. 123, Jakarta'),

                            TextInput::make('rencana_setelah_lulus')
                                ->label('Rencana Setelah Lulus')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: Melanjutkan ke Perguruan Tinggi, Bekerja, dll'),

                            TextInput::make('prestasi')
                                ->label('Prestasi yang Pernah Dicapai')
                                ->maxLength(255)
                                ->placeholder('Contoh: Juara 1 Lomba Matematika, dll'),

                            TextInput::make('pelajaran_favorit')
                                ->label('Pelajaran Favorit')
                                ->maxLength(255)
                                ->placeholder('Contoh: Matematika, Bahasa Inggris, dll'),

                            FileUpload::make('file_ktp')
                                ->label('File KTP')
                                ->required()
                                ->placeholder('Upload File KTP'),
                            FileUpload::make('file_ijazah')
                                ->label('File Ijazah')
                                ->required()
                                ->placeholder('Upload File Ijazah'),
                        ])
                        ->columns(2),

                    Tabs\Tab::make('Data Ayah')
                        ->schema([
                            // Tambahkan komponen form Data Ayah
                            TextInput::make('nama_ayah')
                                ->label('Nama Ayah')
                                ->maxLength(255),
                            TextInput::make('status_ayah')
                                ->label('Status Ayah')
                                ->maxLength(50)
                                ->placeholder('Contoh: Hidup, Meninggal, Cerai'),
                            DatePicker::make('ttl_ayah')
                                ->label('Tanggal Lahir Ayah'),
                            TextInput::make('no_ktp_ayah')
                                ->label('No. KTP Ayah')
                                ->maxLength(50),
                            TextInput::make('pendidikan_ayah')
                                ->label('Pendidikan Ayah')
                                ->maxLength(100)
                                ->placeholder('Contoh: SD, SMP, SMA, S1, S2'),
                            Textarea::make('alamat_ayah')
                                ->label('Alamat Ayah')
                                ->placeholder('Masukkan alamat lengkap'),
                            TextInput::make('profesi_ayah')
                                ->label('Profesi Ayah')
                                ->maxLength(100),
                            TextInput::make('pendapatan_ayah')
                                ->label('Pendapatan Ayah')
                                ->numeric()
                                ->prefix('Rp')
                                ->maxValue(999999999999999.99),
                            TextInput::make('no_hp_ayah')
                                ->label('No. HP Ayah')
                                ->maxLength(50)
                                ->tel()
                                ->placeholder('Contoh: 08123456789'),
                            TextInput::make('email_ayah')
                                ->label('Email Ayah')
                                ->maxLength(255)
                                ->email()
                                ->placeholder('Contoh: ayah@example.com')

                        ]),

                    Tabs\Tab::make('Data Ibu')
                        ->schema([
                            // Tambahkan komponen form Data Ibu

                            TextInput::make('nama_ibu')
                                ->label('Nama Ibu')
                                ->maxLength(255),
                            TextInput::make('status_ibu')
                                ->label('Status Ibu')
                                ->maxLength(50)
                                ->placeholder('Contoh: Hidup, Meninggal, Cerai'),
                            DatePicker::make('ttl_ibu')
                                ->label('Tanggal Lahir Ibu'),
                            TextInput::make('no_ktp_ibu')
                                ->label('No. KTP Ibu')
                                ->maxLength(50),
                            TextInput::make('pendidikan_ibu')
                                ->label('Pendidikan Ibu')
                                ->maxLength(100)
                                ->placeholder('Contoh: SD, SMP, SMA, S1, S2'),
                            Textarea::make('alamat_ibu')
                                ->label('Alamat Ibu')
                                ->placeholder('Masukkan alamat lengkap'),
                            TextInput::make('profesi_ibu')
                                ->label('Profesi Ibu')
                                ->maxLength(100),
                            TextInput::make('pendapatan_ibu')
                                ->label('Pendapatan Ibu')
                                ->numeric()
                                ->prefix('Rp')
                                ->maxValue(999999999999999.99),
                            TextInput::make('no_hp_ibu')
                                ->label('No. HP Ibu')
                                ->maxLength(50)
                                ->tel()
                                ->placeholder('Contoh: 08123456789'),
                            TextInput::make('email_ibu')
                                ->label('Email Ibu')
                                ->maxLength(255)
                                ->email()
                                ->placeholder('Contoh: ibu@example.com')
                        ]),



                    Tabs\Tab::make('Data Wali')
                        ->schema([
                            // Tambahkan komponen form Data Wali
                            Placeholder::make('info_nama_wali')
                                ->label('')
                                ->content('*) Ini tidak wajib diisi jika orang tua peserta didik masih hidup.'),

                            TextInput::make('nama_wali')
                                ->label('Nama Wali')
                                ->maxLength(255),
                            TextInput::make('status_wali')
                                ->label('Status Wali')
                                ->maxLength(50)
                                ->placeholder('Contoh: Hidup, Meninggal, Cerai'),
                            DatePicker::make('ttl_wali')
                                ->label('Tanggal Lahir Wali'),
                            TextInput::make('no_ktp_wali')
                                ->label('No. KTP Wali')
                                ->maxLength(50),
                            TextInput::make('pendidikan_wali')
                                ->label('Pendidikan Wali')
                                ->maxLength(100)
                                ->placeholder('Contoh: SD, SMP, SMA, S1, S2'),
                            Textarea::make('alamat_wali')
                                ->label('Alamat Wali')
                                ->placeholder('Masukkan alamat lengkap'),
                            TextInput::make('profesi_wali')
                                ->label('Profesi Wali')
                                ->maxLength(100),
                            TextInput::make('pendapatan_wali')
                                ->label('Pendapatan Wali')
                                ->numeric()
                                ->prefix('Rp')
                                ->maxValue(999999999999999.99),
                            TextInput::make('no_hp_wali')
                                ->label('No. HP Wali')
                                ->maxLength(50)
                                ->tel()
                                ->placeholder('Contoh: 08123456789'),
                            TextInput::make('email_wali')
                                ->label('Email Wali')
                                ->maxLength(255)
                                ->email()
                                ->placeholder('Contoh: wali@example.com')
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lengkap')->label('Nama')->searchable(),
                TextColumn::make('jurusan.nama')->label('Jurusan'),
                TextColumn::make('nisn')->label('Nomor Induk Siswa Nasional (NISN)'),
                TextColumn::make('status_ppdb')
                    ->label('Status Peserta Didik')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Peserta Baru' => 'info',
                        'Peserta Aktif' => 'warning',
                        'Siswa Baru' => 'success',
                        'Menunggu Mendapat Kelas' => 'warning',
                        'Siswa' => 'danger',
                        default => 'secondary',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('Panitia Ppdb') || Auth::user()->hasRole('Admin') ||  Auth::user()->hasRole('Super Admin');;
    }
}
