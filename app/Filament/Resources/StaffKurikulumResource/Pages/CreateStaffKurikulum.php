<?php

namespace App\Filament\Resources\StaffKurikulumResource\Pages;

use App\Filament\Resources\StaffKurikulumResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateStaffKurikulum extends CreateRecord
{
    protected static string $resource = StaffKurikulumResource::class;

    protected function afterCreate(): void
    {
        $staff = $this->record;

        if ($staff->email) {
            $user = User::where('email', $staff->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $staff->nama,
                    'email' => $staff->email,
                    'password' => Hash::make($staff->email), // password sama dengan email
                ]);

                $role = Role::firstOrCreate(['name' => 'Staff Kurikulum']);
                $user->assignRole($role);

                $staff->user_id = $user->id;
                $staff->save();
            }
        }
    }
}
