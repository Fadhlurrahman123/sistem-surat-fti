<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        User::updateOrCreate(
        ['emailyarsi' => 'mahasiswa.account1@yarsi.co.id'],
        [
            'username'      => 'mahasiswa1',
            'displayname'   => 'mahasiswa account1',
            'password'      => Hash::make('password'),
            'study_program' => 'Teknik Informatika',
            'serial_number' => '1402021045',
            'role'          => 'M',
        ]
    );

    User::updateOrCreate(
        ['emailyarsi' => 'mahasiswa.account2@yarsi.co.id'],
        [
            'username'      => 'mahasiswa2',
            'displayname'   => 'mahasiswa account2',
            'password'      => Hash::make('password'),
            'study_program' => 'Perpustakaan dan Sains Informasi',
            'serial_number' => '1502021045',
            'role'          => 'M',
        ]
    );

    User::updateOrCreate(
        ['emailyarsi' => 'dosen.account1@yarsi.co.id'],
        [
            'username'      => 'dosen1',
            'displayname'   => 'dosen account1',
            'password'      => Hash::make('password'),
            'study_program' => 'Teknik Informatika',
            'serial_number' => '0102021045',
            'role'          => 'D',
        ]
    );

    User::updateOrCreate(
        ['emailyarsi' => 'dosen.account2@yarsi.co.id'],
        [
            'username'      => 'dosen2',
            'displayname'   => 'dosen account2',
            'password'      => Hash::make('password'),
            'study_program' => 'Perpustakaan dan Sains Informasi',
            'serial_number' => '0102021046',
            'role'          => 'D',
        ]
    );

    User::updateOrCreate(
        ['emailyarsi' => 'kaprodi.account1@yarsi.co.id'],
        [
            'username'      => 'kaprodi1',
            'displayname'   => 'kaprodi account1',
            'password'      => Hash::make('password'),
            'study_program' => 'Teknik Informatika',
            'serial_number' => '0202021045',
            'role'          => 'K',
        ]
    );

    User::updateOrCreate(
        ['emailyarsi' => 'kaprodi.account2@yarsi.co.id'],
        [
            'username'      => 'kaprodi2',
            'displayname'   => 'kaprodi account2',
            'password'      => Hash::make('password'),
            'study_program' => 'Perpustakaan dan Sains Informasi',
            'serial_number' => '0202021046',
            'role'          => 'K',
        ]
    );

    User::updateOrCreate(
        ['emailyarsi' => 'dekan.account1@yarsi.co.id'],
        [
            'username'      => 'dekan1',
            'displayname'   => 'kaprodi account2',
            'password'      => Hash::make('password'),
            'faculty'       => 'Teknologi Informasi',
            'serial_number' => '0202021046',
            'role'          => 'DK',
        ]
    );

    User::updateOrCreate(
        ['emailyarsi' => 'stafftu@yarsi.co.id'],
        [
            'username'    => 'stafftu',
            'displayname' => 'Staff TU',
            'password'    => Hash::make('tu123'),
            'role'        => 'TU',
        ]
    );



        $now = Carbon::now();

        // DB::table('users')->insert([
        //     [
        //         'username' => 'mahasiswa1',
        //         'displayname' => 'Mahasiswa Fulan',
        //         'email' => 'mahasiswa@gmail.com',
        //         'emailyarsi' => 'mahasiswa123@yarsi.ac.id',
        //         'password' => Hash::make('password123'),
        //         'role' => 'M',
        //         'serial_number' => '1402020100',
        //         'study_program' => 'Teknik Informatika',
        //         'faculty' => 'Teknologi Informasi',
        //         'telephonenumber' => '087111110000',
        //     ],


        //     [

        //         'username' => 'dosen1',
        //         'displayname' => 'Dosen Fulan',
        //         'email' => 'dosen@gmail.com',
        //         'emailyarsi' => 'dosen123@yarsi.ac.id',
        //         'password' => Hash::make('password123'),
        //         'role' => 'D',
        //         'serial_number' => '0002020100',
        //         'telephonenumber' => '087111110002',
        //     ],


        // ]);
    }
}
