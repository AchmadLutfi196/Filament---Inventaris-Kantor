<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data kategori yang ada (opsional, gunakan jika ingin memastikan data bersih)
        // DB::table('kategoris')->truncate(); // Gunakan ini hanya jika tidak ada foreign key constraints atau sedang dalam proses migrate:fresh

        $kategoris = [
            ['nama' => 'Furniture', 'kode' => 'FUR', 'deskripsi' => 'Perabotan kantor seperti meja, kursi, lemari'],
            ['nama' => 'Elektronik', 'kode' => 'ELK', 'deskripsi' => 'Peralatan elektronik kantor'],
            ['nama' => 'Alat Tulis Kantor', 'kode' => 'ATK', 'deskripsi' => 'Alat tulis dan keperluan kantor'],
            ['nama' => 'Kendaraan', 'kode' => 'KND', 'deskripsi' => 'Kendaraan operasional kantor'],
            ['nama' => 'Peralatan', 'kode' => 'PRL', 'deskripsi' => 'Peralatan pendukung operasional']
        ];

        foreach ($kategoris as $kategori) {
            // Menggunakan updateOrCreate untuk menghindari duplikasi
            Kategori::updateOrCreate(
                ['kode' => $kategori['kode']], // Kriteria pencarian berdasarkan kode
                $kategori // Data yang akan dimasukkan atau diperbarui
            );
        }
    }
}