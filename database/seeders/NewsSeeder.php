<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (News::count() > 0) {
            return;
        }

        $journalist = User::where('email', 'journalist@test.ru')->first();
        $admin = User::where('email', 'admin@test.ru')->first();

        $aiCat = Category::firstOrCreate(['name' => 'Искусственный интеллект']);
        $techCat = Category::firstOrCreate(['name' => 'Технологии']);
        $spaceCat = Category::firstOrCreate(['name' => 'Космос']);

        News::create([
            'title' => 'Прорыв в сфере ИИ: Новые нейросети обучаются быстрее',
            'category_id' => $aiCat->id,
            'user_id' => $journalist?->id,
            'content' => 'Ученые представили обновленную архитектуру нейросетей, которая позволяет снизить потребление энергии на 40% и ускорить процесс генерации ответов. Технология уже проходит тестирование в ведущих лабораториях мира.',
        ]);

        News::create([
            'title' => 'Искусственный интеллект научился прогнозировать погоду на месяц вперед',
            'category_id' => $aiCat->id,
            'user_id' => $journalist?->id,
            'content' => 'Метеорологи начали применять специализированную ИИ-модель, анализирующую миллионы спутниковых снимков и показателей давления. Точность долгосрочных прогнозов выросла на 25%.',
        ]);

        News::create([
            'title' => 'Разработка квантовых процессоров нового поколения',
            'category_id' => $techCat->id,
            'user_id' => $admin?->id,
            'content' => 'Инженеры продемонстрировали чип с рекордно низким уровнем шума кубитов. Это приближает коммерческое использование квантовых вычислений для решения сложных научных задач.',
        ]);

        News::create([
            'title' => 'Новые автономные роботы-помощники с интеграцией ИИ',
            'category_id' => $aiCat->id,
            'user_id' => $journalist?->id,
            'content' => 'Компания показала антропоморфного робота, способного понимать голосовые команды на естественном языке и выполнять бытовые поручения с помощью встроенных ИИ-алгоритмов зрения.',
        ]);

        News::create([
            'title' => 'Запуск космической миссии к дальним астероидам',
            'category_id' => $spaceCat->id,
            'user_id' => $admin?->id,
            'content' => 'С космодрома успешно стартовала исследовательская станция, целью которой является забор образцов грунта и поиск редких минералов на астероидах главного пояса.',
        ]);
    }
}
