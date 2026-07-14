<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed data pengguna default sesuai struktur role FIKES UIS.
     *
     * Role yang tersedia (dari Checkrole::ROLES):
     * - admin                     → Admin Sistem
     * - koordinatorakreditasifikes → Koordinator Akreditasi FIKes
     * - koordinatorprodi           → Koordinator Prodi
     * - timpenyusun                → Tim Penyusun (Dosen)
     * - gpmfikes                   → GPM FIKes
     * - timlpmrektorat             → Tim LPM/Rektorat
     * - dekan                      → Pimpinan (Dekan)
     */
    public function run(): void
    {
        $users = [
            [
                'name'      => 'Administrator Sistem',
                'email'     => 'admin@fikes.uis.ac.id',
                'password'  => Hash::make('Admin@123'),
                'role'      => 'admin',
                'is_active' => true,
            ],
            [
                'name'      => 'Koordinator Akreditasi FIKes',
                'email'     => 'koordinator.akreditasi@fikes.uis.ac.id',
                'password'  => Hash::make('Koor@123'),
                'role'      => 'koordinatorakreditasifikes',
                'is_active' => true,
            ],
            [
                'name'      => 'Koordinator Program Studi',
                'email'     => 'koordinator.prodi@fikes.uis.ac.id',
                'password'  => Hash::make('Koor@123'),
                'role'      => 'koordinatorprodi',
                'is_active' => true,
            ],
            [
                'name'      => 'Tim Penyusun Dosen',
                'email'     => 'timpenyusun@fikes.uis.ac.id',
                'password'  => Hash::make('Tim@123'),
                'role'      => 'timpenyusun',
                'is_active' => true,
            ],
            [
                'name'      => 'GPM FIKes',
                'email'     => 'gpmfikes@fikes.uis.ac.id',
                'password'  => Hash::make('Gpm@123'),
                'role'      => 'gpmfikes',
                'is_active' => true,
            ],
            [
                'name'      => 'Tim LPM Rektorat',
                'email'     => 'timlpm@fikes.uis.ac.id',
                'password'  => Hash::make('Lpm@123'),
                'role'      => 'timlpmrektorat',
                'is_active' => true,
            ],
            [
                'name'      => 'Dekan FIKES',
                'email'     => 'dekan@fikes.uis.ac.id',
                'password'  => Hash::make('Dekan@123'),
                'role'      => 'dekan',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
