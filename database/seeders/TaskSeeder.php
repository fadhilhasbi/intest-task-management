<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Mengambil beberapa pengguna untuk dipasangkan dengan task
          $users = User::all(); // Pastikan tabel users sudah memiliki data

          if ($users->isEmpty()) {
              $this->command->info('Tidak ada pengguna ditemukan. Task tidak dapat diisi.');
              return;
          }

          // Inisialisasi Faker untuk menghasilkan data acak
          $faker = Faker::create('id_ID'); // Menggunakan locale Indonesia

          // Membuat 50 task
          foreach (range(1, 50) as $index) {
              Task::create([
                  'title' => $faker->sentence(), // Judul task dalam Bahasa Indonesia
                  'description' => $faker->paragraph(), // Deskripsi task dalam Bahasa Indonesia
                  'user_id' => $users->random()->id, // Menambahkan user_id acak dari data user
                  'status' => $faker->randomElement(['incomplete', 'completed']), // Status acak
              ]);
          }
    }
}
