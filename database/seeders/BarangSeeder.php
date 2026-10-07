<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barang = [];
        $counterKode = 1;

        $templateBarang = [
            [
                'kategori'      => 'Blouse',
                'nama'          => 'Casual Cotton',
                'lingkar'       => 98,
                'panjang'       => 65,
                'harga_beli'    => 35000,
                'harga_jual'    => 65000
            ],
            [
                'kategori'      => 'Kemeja',
                'nama'          => 'Slim Fit Polos',
                'lingkar'       => 102,
                'panjang'       => 72,
                'harga_beli'    => 40000,
                'harga_jual'    => 75000
            ],
            [
                'kategori'      => 'Rok',
                'nama'          => 'Plisket Panjang',
                'lingkar'       => 70,
                'panjang'       => 92,
                'harga_beli'    => 45000,
                'harga_jual'    => 80000
            ],
            [
                'kategori'      => 'Celana',
                'nama'          => 'Chino Reguler',
                'lingkar'       => 82,
                'panjang'       => 100,
                'harga_beli'    => 50000,
                'harga_jual'    => 95000
            ],
            [
                'kategori'      => 'Overall',
                'nama'          => 'Denim Style',
                'lingkar'       => 96,
                'panjang'       => 130,
                'harga_beli'    => 65000,
                'harga_jual'    => 120000
            ],
        ];

        for ($pembelianId = 1; $pembelianId <= 20; $pembelianId++) {
            foreach ($templateBarang as $tb) {
                $kodeNomor = str_pad($counterKode, 4, '0', STR_PAD_LEFT);

                $barang[] = [
                    'kode'         => "BRG-202610-{$kodeNomor}",
                    'nama'         => "{$tb['kategori']} {$tb['nama']}",
                    'lingkar'      => $tb['lingkar'],
                    'panjang'      => $tb['panjang'],
                    'kategori'     => $tb['kategori'],
                    'harga_beli'   => $tb['harga_beli'],
                    'harga_jual'   => $tb['harga_jual'],
                    'status'       => 'Tersedia',
                    'pembelian_id' => $pembelianId,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];

                $counterKode++;
            }
        }

        DB::table('barang')->insert($barang);
    }
}
