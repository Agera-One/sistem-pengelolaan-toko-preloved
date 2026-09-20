<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name'          => 'Yani Tristanti',
            'email'         => 'yanitristanti@gmail.com',
            'password'      => Hash::make('yani123'),
            'nomor_telepon' => '0881010788808',
            'alamat'        => 'Jl. Graha Angkasa RRI Surabaya No.A15, Balongpoh, Kedungrejo, Kec. Waru, Kabupaten Sidoarjo, Jawa Timur',
        ]);

    }
}
