<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kategori;

class KategoriFactory extends Factory
{
    public function definition(): array
    {
        // Daftar kategori tetap yang sama dengan seeder
        $kategoris = [
            ['nama' => 'Furniture', 'kode' => 'FUR', 'deskripsi' => 'Perabotan kantor seperti meja, kursi, lemari'],
            ['nama' => 'Elektronik', 'kode' => 'ELK', 'deskripsi' => 'Peralatan elektronik kantor'],
            ['nama' => 'Alat Tulis Kantor', 'kode' => 'ATK', 'deskripsi' => 'Alat tulis dan keperluan kantor'],
            ['nama' => 'Kendaraan', 'kode' => 'KND', 'deskripsi' => 'Kendaraan operasional kantor'],
            ['nama' => 'Peralatan', 'kode' => 'PRL', 'deskripsi' => 'Peralatan pendukung operasional']
        ];
        
        // Ambil kategori yang sudah ada di database
        $existingCodes = Kategori::pluck('kode')->toArray();
        
        // Filter kategori yang belum ada di database
        $availableKategoris = array_filter($kategoris, function($kategori) use ($existingCodes) {
            return !in_array($kategori['kode'], $existingCodes);
        });
        
        // Jika semua kategori sudah ada, buat kategori tambahan atau kembali ke kategori yang ada
        if (empty($availableKategoris)) {
            $kategori = $this->faker->randomElement($kategoris);
        } else {
            $kategori = $this->faker->randomElement($availableKategoris);
        }
        
        return [
            'nama' => $kategori['nama'],
            'kode' => $kategori['kode'],
            'deskripsi' => $kategori['deskripsi']
        ];
    }
}