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
        // 1. Создаем администратора (если еще не создан)
        User::firstOrCreate(
            ['email' => 'admin@test.ru'],
            [
                'name' => 'Администратор сайта',
                'password' => Hash::make('12345'),
                'role' => 'admin',
            ]
        );

        // 2. Создаем журналиста (если еще не создан)
        User::firstOrCreate(
            ['email' => 'journalist@test.ru'],
            [
                'name' => 'Иван Журналист',
                'password' => Hash::make('12345'),
                'role' => 'journalist',
            ]
        );

        // 3. Создаем обычного читателя (если еще не создан)
        User::firstOrCreate(
            ['email' => 'user@test.ru'],
            [
                'name' => 'Алексей Читатель',
                'password' => Hash::make('12345'),
                'role' => 'user',
            ]
        );

        // Сначала создаем категории
        $this->call(CategorySeeder::class);

        // Наполняем новостями (с привязкой к категориям и авторам)
        $this->call(NewsSeeder::class);
    }
}
