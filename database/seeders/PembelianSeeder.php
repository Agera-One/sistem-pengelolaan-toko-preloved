<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        $templateBarang = [
            ['harga_beli' => 35000],
            ['harga_beli' => 40000],
            ['harga_beli' => 45000],
            ['harga_beli' => 50000],
            ['harga_beli' => 65000],
        ];

        $totalHargaBeli = array_sum(array_column($templateBarang, 'harga_beli'));

        $pembelian = [];

        for ($i = 1; $i <= 20; $i++) {
            $hari = str_pad($i, 2, '0', STR_PAD_LEFT);
            $kodeNomor = str_pad($i, 4, '0', STR_PAD_LEFT);

            $pembelian[] = [
                'tanggal'     => "2026-09-{$hari}",
                'kode'        => "BEL-202610-{$kodeNomor}",
                'total'       => $totalHargaBeli,
                'status'      => 'Belum Bayar',
                'user_id'     => 1,
                'supplier_id' => $i,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        DB::table('pembelian')->insert($pembelian);
    }
}
