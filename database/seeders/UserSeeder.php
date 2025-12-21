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
            'serial_number' => '1402021045',
            'role'          => 'M',
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
