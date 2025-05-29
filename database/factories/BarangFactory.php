<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    public function definition(): array
    {
        // Ambil ID kategori yang sudah ada di database
        $kategoriIds = Kategori::pluck('id')->toArray();
        
        // Jika tidak ada kategori, gunakan KategoriSeeder terlebih dahulu
        if (empty($kategoriIds)) {
            // Panggil KategoriSeeder secara programatis
            $seeder = new \Database\Seeders\KategoriSeeder();
            $seeder->run();
            
            // Ambil ID kategori setelah seeding
            $kategoriIds = Kategori::pluck('id')->toArray();
        }
        
        return [
            'nama' => $this->faker->words(3, true),
            'kode_barang' => 'BRG-' . $this->faker->unique()->numberBetween(1000, 9999),
            'kategori_id' => $this->faker->randomElement($kategoriIds), // Pilih dari kategori yang sudah ada
            'deskripsi' => $this->faker->paragraph(),
            'stok' => $this->faker->numberBetween(1, 20),
            'kondisi' => $this->faker->randomElement(['baik', 'rusak ringan', 'perlu perbaikan']),
            'lokasi' => $this->faker->randomElement(['Lantai 1', 'Lantai 2', 'Gudang', 'Ruang Server']),
            'tersedia' => $this->faker->boolean(85)
        ];
    }
}