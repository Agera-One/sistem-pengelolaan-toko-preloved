<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pelanggan')->insert([
            'kode'          => 'PLG-202609-0001',
            'nama'          => 'Yani Tristanti',
            'nomor_telepon' => '0881010788808',
            'alamat'        => 'Jl. Graha Angkasa RRI Surabaya No.A15, Balongpoh, Kedungrejo, Kec. Waru, Kabupaten Sidoarjo, Jawa Timur',
        ]);
    }
}
