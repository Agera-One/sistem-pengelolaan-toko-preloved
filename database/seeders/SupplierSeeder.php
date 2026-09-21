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
                'kode'          => 'SPL-202609-0001',
                'nama'          => 'PT Pratama Jaya Abadi',
                'nomor_telepon' => '081234567801',
                'kota'          => 'Surabaya',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0002',
                'nama'          => 'CV Rahmawati Murni',
                'nomor_telepon' => '082145678902',
                'kota'          => 'Malang',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0003',
                'nama'          => 'PT Santoso Gemilang',
                'nomor_telepon' => '083156789013',
                'kota'          => 'Sidoarjo',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0004',
                'nama'          => 'UD Lestari Mandiri',
                'nomor_telepon' => '085267890124',
                'kota'          => 'Gresik',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0005',
                'nama'          => 'PT Maulana Logistics',
                'nomor_telepon' => '081378901235',
                'kota'          => 'Surabaya',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0006',
                'nama'          => 'CV Aisyah Berkah',
                'nomor_telepon' => '082289012346',
                'kota'          => 'Mojokerto',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0007',
                'nama'          => 'PT Nugroho Tekno',
                'nomor_telepon' => '083390123457',
                'kota'          => 'Kediri',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0008',
                'nama'          => 'UD Amelia Pangan',
                'nomor_telepon' => '085401234568',
                'kota'          => 'Pasuruan',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0009',
                'nama'          => 'PT Saputra Sentosa',
                'nomor_telepon' => '081512345679',
                'kota'          => 'Surabaya',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0010',
                'nama'          => 'CV Wulandari Utama',
                'nomor_telepon' => '082623456780',
                'kota'          => 'Lamongan',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0011',
                'nama'          => 'PT Firmansyah Sukses',
                'nomor_telepon' => '083734567891',
                'kota'          => 'Jombang',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0012',
                'nama'          => 'UD Permata Indah',
                'nomor_telepon' => '085845678912',
                'kota'          => 'Surabaya',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0013',
                'nama'          => 'PT Hidayat Karya',
                'nomor_telepon' => '081956789023',
                'kota'          => 'Banyuwangi',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0014',
                'nama'          => 'CV Marlina Teknik',
                'nomor_telepon' => '082067890134',
                'kota'          => 'Probolinggo',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0015',
                'nama'          => 'PT Setiawan Cemerlang',
                'nomor_telepon' => '083178901245',
                'kota'          => 'Surabaya',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0016',
                'nama'          => 'UD Sari Mulia',
                'nomor_telepon' => '085289012356',
                'kota'          => 'Madiun',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0017',
                'nama'          => 'PT Kurniawan Sejahtera',
                'nomor_telepon' => '081390123467',
                'kota'          => 'Sidoarjo',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0018',
                'nama'          => 'CV Putri Busana',
                'nomor_telepon' => '082401234578',
                'kota'          => 'Malang',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0019',
                'nama'          => 'PT Ramadhan Perdana',
                'nomor_telepon' => '083512345689',
                'kota'          => 'Surabaya',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'kode'          => 'SPL-202609-0020',
                'nama'          => 'UD Anggraini Mas',
                'nomor_telepon' => '085623456790',
                'kota'          => 'Gresik',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
