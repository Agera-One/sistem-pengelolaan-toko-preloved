<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranPembelianSeeder extends Seeder
{
    public function run(): void
    {
        $metodePembayaran = ['Tunai', 'Transfer'];

        $pembayarans = [
            ['pembelian_id' => 1,  'tanggal' => '2026-09-01', 'nominal' => 1500000],
            ['pembelian_id' => 2,  'tanggal' => '2026-09-02', 'nominal' => 2300000],
            ['pembelian_id' => 3,  'tanggal' => '2026-09-03', 'nominal' => 750000],
            ['pembelian_id' => 4,  'tanggal' => '2026-09-04', 'nominal' => 3100000],
            ['pembelian_id' => 5,  'tanggal' => '2026-09-05', 'nominal' => 1250000],
            ['pembelian_id' => 6,  'tanggal' => '2026-09-06', 'nominal' => 4500000],
            ['pembelian_id' => 7,  'tanggal' => '2026-09-07', 'nominal' => 890000],
            ['pembelian_id' => 8,  'tanggal' => '2026-09-08', 'nominal' => 2100000],
            ['pembelian_id' => 9,  'tanggal' => '2026-09-09', 'nominal' => 1750000],
            ['pembelian_id' => 10, 'tanggal' => '2026-09-10', 'nominal' => 5000000],
            ['pembelian_id' => 11, 'tanggal' => '2026-09-11', 'nominal' => 980000],
            ['pembelian_id' => 12, 'tanggal' => '2026-09-12', 'nominal' => 3400000],
            ['pembelian_id' => 13, 'tanggal' => '2026-09-13', 'nominal' => 1600000],
            ['pembelian_id' => 14, 'tanggal' => '2026-09-14', 'nominal' => 2750000],
            ['pembelian_id' => 15, 'tanggal' => '2026-09-15', 'nominal' => 6200000],
            ['pembelian_id' => 16, 'tanggal' => '2026-09-16', 'nominal' => 1150000],
            ['pembelian_id' => 17, 'tanggal' => '2026-09-17', 'nominal' => 4100000],
            ['pembelian_id' => 18, 'tanggal' => '2026-09-18', 'nominal' => 850000],
            ['pembelian_id' => 19, 'tanggal' => '2026-09-19', 'nominal' => 2900000],
            ['pembelian_id' => 20, 'tanggal' => '2026-09-20', 'nominal' => 1800000],
        ];

        $data = [];

        foreach ($pembayarans as $index => $pembayaran) {
            $i = $index + 1;

            $ym = date('Ym', strtotime($pembayaran['tanggal']));

            $kode = 'BYE-' . $ym . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $data[] = [
                'kode'              => $kode,
                'tanggal'           => $pembayaran['tanggal'],
                'nominal'           => $pembayaran['nominal'],
                'metode_pembayaran' => $metodePembayaran[array_rand($metodePembayaran)],
                'pembelian_id'      => $pembayaran['pembelian_id'],
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        DB::table('pembayaran_pembelian')->insert($data);
    }
}
