<?php

namespace App\Filament\Resources\GuruResource\Pages;

use App\Filament\Resources\GuruResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log; // import Log facade
use Mockery\Generator\Generator;

class CreateGuru extends CreateRecord
{
    protected static string $resource = GuruResource::class;

    protected function afterCreate(): void
    {
        $guru = $this->record;

        Log::info('afterCreate triggered', ['guru' => $guru->toArray()]); // log data guru

        $guru->password = 'Guru123';

        if ($guru->email && $guru->password) {
            $user = User::where('email', $guru->email)->first();

            Log::info('Checking existing user', ['user_found' => $user ? true : false]);

            if (!$user) {
                $user = User::create([
                    'name' => $guru->nama,
                    'email' => $guru->email,
                    'password' => Hash::make($guru->email), // pastikan sudah hashed jika perlu
                ]);

                Log::info('User created', ['user' => $user->toArray()]);

                $roleGuru = Role::where('name', 'Guru')->first();

                if ($roleGuru) {
                    $user->assignRole($roleGuru);
                    Log::info('Role assigned: Guru');
                } else {
                    Log::warning('Role "Guru" not found');
                }
            } else {
                Log::info('User already exists, skipping create');
            }
        } else {
            Log::warning('Guru email or password missing');
        }
    }
}
