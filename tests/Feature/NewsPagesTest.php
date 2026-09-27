<?php

namespace Tests\Feature;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_accessible(): void
    {
        News::create([
            'title' => 'Тестовая новость',
            'category' => 'Технологии',
            'content' => 'Текст тестовой новости',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Лента всех новостей');
        $response->assertSee('Тестовая новость');
    }

    public function test_catalog_page_is_accessible_and_filters(): void
    {
        News::create([
            'title' => 'Новость про ИИ',
            'category' => 'Искусственный интеллект',
            'content' => 'Содержание про ИИ',
        ]);

        $response = $this->get(route('catalog'));

        $response->assertStatus(200);
        $response->assertSee('Каталог: Искусственный интеллект');
        $response->assertSee('Новость про ИИ');
    }

    public function test_journalist_page_can_create_news(): void
    {
        $response = $this->get(route('journalist'));
        $response->assertStatus(200);
        $response->assertSee('Панель журналиста');

        $postResponse = $this->post(route('journalist.store'), [
            'title' => 'Новая созданная новость',
            'category' => 'Искусственный интеллект',
            'content' => 'Текст новой созданной новости',
        ]);

        $postResponse->assertRedirect(route('home'));
        $this->assertDatabaseHas('news', [
            'title' => 'Новая созданная новость',
        ]);
    }

    public function test_admin_page_can_delete_news(): void
    {
        $news = News::create([
            'title' => 'Новость для удаления',
            'category' => 'Космос',
            'content' => 'Содержимое',
        ]);

        $response = $this->get(route('admin'));
        $response->assertStatus(200);
        $response->assertSee('Панель администратора');
        $response->assertSee('Новость для удаления');

        $deleteResponse = $this->delete(route('admin.destroy', $news));
        $deleteResponse->assertRedirect(route('admin'));

        $this->assertDatabaseMissing('news', [
            'id' => $news->id,
        ]);
    }
}
