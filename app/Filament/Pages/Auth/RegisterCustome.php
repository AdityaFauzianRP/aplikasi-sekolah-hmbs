<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Register;
use Filament\Pages\Page;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use App\Models\PesertaDidik;
use App\Models\Ppdb;


class RegisterCustome extends Register
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Select::make('ppdb_id')
                            ->label('Gelombang PPDB')
                            ->required()
                            ->options(Ppdb::pluck('judul_ppdb', 'id')) // Ambil manual
                            ->searchable()
                            ->preload(),
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        TextInput::make('nisn')
                            ->label('NISN')
                            ->required()
                            ->maxLength(10),

                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->required()
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ]),

                        TextInput::make('asal_sekolah')
                            ->label('Asal Sekolah')
                            ->required()
                            ->maxLength(255),

                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('filament-panels::pages/auth/register.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/register.form.email.label'))
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/register.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(Password::default())
            ->dehydrateStateUsing(fn($state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute'));
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    protected function handleRegistration(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // Buat user terlebih dahulu
            $user = $this->getUserModel()::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'], // Pastikan password di-hash
            ]);

            // Ambil ID dari user yang baru dibuat
            $userId = $user->id;

            $tahun = date('Y');
            $prefix = 'PPDB-' . $tahun . '-';

            // Ambil ID terakhir peserta di tahun ini (optional bisa pakai nomor_registrasi kalau sudah ada sebelumnya)
            $lastNumber = DB::table('peserta_didik')
                ->whereYear('created_at', $tahun)
                ->count() + 1;

            // Format 00001, 00002, dst
            $nomorUrut = str_pad($lastNumber, 5, '0', STR_PAD_LEFT);
            $nomorRegistrasi = $prefix . $nomorUrut;

            // Buat peserta didik dan simpan ID user ke kolom user_id
            $pesertaDidik = PesertaDidik::create([
                'nama_lengkap'      => $data['name'],
                'email'             => $data['email'],
                'password'          => $user->password, // Gunakan password yang sama
                'nisn'              => $data['nisn'],
                'jenis_kelamin'     => $data['jenis_kelamin'],
                'asal_sekolah'      => $data['asal_sekolah'],
                'ppdb_id'           => $data['ppdb_id'],
                'jurusan_id'        => 1, // default
                'user_id'           => $userId, // Menyimpan ID user yang baru dibuat
                'status_ppdb'       => 'Peserta Baru',
                'nomor_registrasi'  => $nomorRegistrasi,
            ]);

            // Assign role ke user
            $user->assignRole('Peserta');

            return $user;
        });
    }
}
