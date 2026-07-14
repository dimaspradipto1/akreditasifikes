<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed data pengguna default untuk sistem akreditasi FIKES UIS
     */
    public function run(): void
    {
        // Admin Utama
        User::updateOrCreate(
            ['email' => 'admin@fikes.uis.ac.id'],
            [
                'name'      => 'Administrator FIKES',
                'email'     => 'admin@fikes.uis.ac.id',
                'password'  => Hash::make('Admin@123'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        // Operator
        User::updateOrCreate(
            ['email' => 'operator@fikes.uis.ac.id'],
            [
                'name'      => 'Operator Akreditasi',
                'email'     => 'operator@fikes.uis.ac.id',
                'password'  => Hash::make('Operator@123'),
                'role'      => 'operator',
                'is_active' => true,
            ]
        );

        // Asesor
        User::updateOrCreate(
            ['email' => 'asesor@fikes.uis.ac.id'],
            [
                'name'      => 'Asesor Internal',
                'email'     => 'asesor@fikes.uis.ac.id',
                'password'  => Hash::make('Asesor@123'),
                'role'      => 'asesor',
                'is_active' => true,
            ]
        );

        // Program Studi (Keperawatan)
        User::updateOrCreate(
            ['email' => 'keperawatan@fikes.uis.ac.id'],
            [
                'name'      => 'Prodi Keperawatan',
                'email'     => 'keperawatan@fikes.uis.ac.id',
                'password'  => Hash::make('Prodi@123'),
                'role'      => 'prodi',
                'is_active' => true,
            ]
        );

        // Program Studi (Kesehatan Masyarakat)
        User::updateOrCreate(
            ['email' => 'kesmas@fikes.uis.ac.id'],
            [
                'name'      => 'Prodi Kesehatan Masyarakat',
                'email'     => 'kesmas@fikes.uis.ac.id',
                'password'  => Hash::make('Prodi@123'),
                'role'      => 'prodi',
                'is_active' => true,
            ]
        );
    }
}
