<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'username' => 'mahasiswa1',
                'name' => 'Mahasiswa Fulan',
                'email' => 'mahasiswa@yarsi.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'prodi_id' => 1,
                'npm' => '1402020100',
                'program_studi' => 'Teknik Informatika',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'username' => 'admin1',
                'name' => 'Admin Utama',
                'email' => 'admin@yarsi.ac.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'prodi_id' => null,
                'npm' => null, // admin tidak punya npm
                'program_studi' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'username' => 'dosen1',
                'name' => 'Dosen Pembimbing',
                'email' => 'dosen@yarsi.ac.id',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'prodi_id' => 1,
                'npm' => null,
                'program_studi' => 'Teknik Informatika',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'username' => 'kaprodi1',
                'name' => 'Kaprodi TI',
                'email' => 'kaprodi@yarsi.ac.id',
                'password' => Hash::make('kaprodi123'),
                'role' => 'kaprodi',
                'prodi_id' => 1,
                'npm' => null,
                'program_studi' => 'Teknik Informatika',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'username' => 'dekan1',
                'name' => 'Dekan FTI',
                'email' => 'dekan@yarsi.ac.id',
                'password' => Hash::make('dekan123'),
                'role' => 'dekan',
                'prodi_id' => null,
                'npm' => null,
                'program_studi' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
