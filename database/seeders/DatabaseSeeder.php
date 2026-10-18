<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Создаем администратора
        User::create([
            'name' => 'Администратор сайта',
            'email' => 'admin@test.ru',
            'password' => Hash::make('12345'),
            'role' => 'admin',
        ]);

        // 2. Создаем журналиста
        User::create([
            'name' => 'Иван Журналист',
            'email' => 'journalist@test.ru',
            'password' => Hash::make('12345'),
            'role' => 'journalist',
        ]);

        // 3. Создаем обычного читателя
        User::create([
            'name' => 'Алексей Читатель',
            'email' => 'user@test.ru',
            'password' => Hash::make('12345'),
            'role' => 'user',
        ]);

        // Наполняем новостями
        $this->call(NewsSeeder::class);
    }
}
