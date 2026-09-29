<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PembelianSeeder::class,
            BarangSeeder::class,
            PelangganSeeder::class,
            SupplierSeeder::class,
            PembelianSeeder::class,
        ]);
    }
}
