<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 10 users (password: password)
        User::factory()->count(9)->create();
        User::factory()->create([
            'name' => 'Muhamad Iqbal Dzulkarnaen',
            'email' => 'iqbaldzulkarnaen12@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // 30 buku
        Book::factory()->count(30)->create();
    }
}
