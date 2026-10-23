<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('supplier')->insert([
            [
                'kode'              => 'SPL-202609-0001',
                'nama'              => 'PT Pratama Jaya Abadi',
                'nomor_telepon'     => '081234567801',
                'kabupaten_kota'    => 'Kota Surabaya',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0002',
                'nama'              => 'CV Rahmawati Murni',
                'nomor_telepon'     => '082145678902',
                'kabupaten_kota'    => 'Kota Malang',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0003',
                'nama'              => 'PT Santoso Gemilang',
                'nomor_telepon'     => '083156789013',
                'kabupaten_kota'    => 'Kabupaten Sidoarjo',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0004',
                'nama'              => 'UD Lestari Mandiri',
                'nomor_telepon'     => '085267890124',
                'kabupaten_kota'    => 'Kabupaten Gresik',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0005',
                'nama'              => 'PT Maulana Logistics',
                'nomor_telepon'     => '081378901235',
                'kabupaten_kota'    => 'Kota Surabaya',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0006',
                'nama'              => 'CV Aisyah Berkah',
                'nomor_telepon'     => '082289012346',
                'kabupaten_kota'    => 'Kota Mojokerto',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0007',
                'nama'              => 'PT Nugroho Tekno',
                'nomor_telepon'     => '083390123457',
                'kabupaten_kota'    => 'Kota Kediri',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0008',
                'nama'              => 'UD Amelia Pangan',
                'nomor_telepon'     => '085401234568',
                'kabupaten_kota'    => 'Kota Pasuruan',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0009',
                'nama'              => 'PT Saputra Sentosa',
                'nomor_telepon'     => '081512345679',
                'kabupaten_kota'    => 'Kota Surabaya',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0010',
                'nama'              => 'CV Wulandari Utama',
                'nomor_telepon'     => '082623456780',
                'kabupaten_kota'    => 'Kabupaten Lamongan',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0011',
                'nama'              => 'PT Firmansyah Sukses',
                'nomor_telepon'     => '083734567891',
                'kabupaten_kota'    => 'Kabupaten Jombang',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0012',
                'nama'              => 'UD Permata Indah',
                'nomor_telepon'     => '085845678912',
                'kabupaten_kota'    => 'Kota Surabaya',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0013',
                'nama'              => 'PT Hidayat Karya',
                'nomor_telepon'     => '081956789023',
                'kabupaten_kota'    => 'Kabupaten Banyuwangi',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0014',
                'nama'              => 'CV Marlina Teknik',
                'nomor_telepon'     => '082067890134',
                'kabupaten_kota'    => 'Kota Probolinggo',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0015',
                'nama'              => 'PT Setiawan Cemerlang',
                'nomor_telepon'     => '083178901245',
                'kabupaten_kota'    => 'Kota Surabaya',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0016',
                'nama'              => 'UD Sari Mulia',
                'nomor_telepon'     => '085289012356',
                'kabupaten_kota'    => 'Kota Madiun',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0017',
                'nama'              => 'PT Kurniawan Sejahtera',
                'nomor_telepon'     => '081390123467',
                'kabupaten_kota'    => 'Kabupaten Sidoarjo',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0018',
                'nama'              => 'CV Putri Busana',
                'nomor_telepon'     => '082401234578',
                'kabupaten_kota'    => 'Kota Malang',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0019',
                'nama'              => 'PT Ramadhan Perdana',
                'nomor_telepon'     => '083512345689',
                'kabupaten_kota'    => 'Kota Surabaya',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'kode'              => 'SPL-202609-0020',
                'nama'              => 'UD Anggraini Mas',
                'nomor_telepon'     => '085623456790',
                'kabupaten_kota'    => 'Kabupaten Gresik',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}
