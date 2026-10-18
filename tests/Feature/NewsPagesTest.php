<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_accessible_and_filters_categories(): void
    {
        News::create([
            'title' => 'Тестовая новость ИИ',
            'category' => 'Искусственный интеллект',
            'content' => 'Текст про ИИ',
        ]);

        News::create([
            'title' => 'Тестовая новость Космос',
            'category' => 'Космос',
            'content' => 'Текст про Космос',
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Лента всех новостей');
        $response->assertSee('Тестовая новость ИИ');
        $response->assertSee('Тестовая новость Космос');

        // Фильтрация по категории
        $filteredResponse = $this->get(route('home', ['category' => 'Искусственный интеллект']));
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Тестовая новость ИИ');
        $filteredResponse->assertDontSee('Тестовая новость Космос');
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

    public function test_guest_cannot_access_journalist_or_admin(): void
    {
        $this->get(route('journalist'))->assertRedirect(route('login'));
        $this->get(route('admin'))->assertRedirect(route('login'));
    }

    public function test_user_role_cannot_access_journalist_or_admin(): void
    {
        $user = User::create([
            'name' => 'Обычный юзер',
            'email' => 'user@test.ru',
            'password' => bcrypt('12345'),
            'role' => 'user',
        ]);

        $this->actingAs($user)->get(route('journalist'))->assertStatus(403);
        $this->actingAs($user)->get(route('admin'))->assertStatus(403);
    }

    public function test_journalist_can_create_news_but_cannot_access_admin(): void
    {
        $journalist = User::create([
            'name' => 'Журналист',
            'email' => 'journ@test.ru',
            'password' => bcrypt('12345'),
            'role' => 'journalist',
        ]);

        // Журналист может зайти в раздел /journalist
        $this->actingAs($journalist)->get(route('journalist'))->assertStatus(200);

        // Журналист может опубликовать новость
        $postResponse = $this->actingAs($journalist)->post(route('journalist.store'), [
            'title' => 'Статья от журналиста',
            'category' => 'Искусственный интеллект',
            'content' => 'Текст от журналиста',
        ]);

        $postResponse->assertRedirect(route('home'));
        $this->assertDatabaseHas('news', [
            'title' => 'Статья от журналиста',
        ]);

        // Но не имеет доступа в админку
        $this->actingAs($journalist)->get(route('admin'))->assertStatus(403);
    }

    public function test_admin_has_full_access_and_can_edit_news_and_change_roles(): void
    {
        $admin = User::create([
            'name' => 'Главный админ',
            'email' => 'admin@test.ru',
            'password' => bcrypt('12345'),
            'role' => 'admin',
        ]);

        $reader = User::create([
            'name' => 'Читатель для смены роли',
            'email' => 'reader@test.ru',
            'password' => bcrypt('12345'),
            'role' => 'user',
        ]);

        $news = News::create([
            'title' => 'Исходная новость',
            'category' => 'Технологии',
            'content' => 'Исходный текст',
        ]);

        // 1. Админ заходит в админку
        $response = $this->actingAs($admin)->get(route('admin'));
        $response->assertStatus(200);
        $response->assertSee('Панель администратора');
        $response->assertSee('Исходная новость');

        // 2. Админ редактирует новость
        $editResponse = $this->actingAs($admin)->get(route('admin.news.edit', $news));
        $editResponse->assertStatus(200);

        $updateResponse = $this->actingAs($admin)->put(route('admin.news.update', $news), [
            'title' => 'Отредактированный заголовок',
            'category' => 'Космос',
            'content' => 'Обновленный текст статьи',
        ]);
        $updateResponse->assertRedirect(route('admin'));
        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'title' => 'Отредактированный заголовок',
        ]);

        // 3. Админ меняет роль пользователю (по требованию преподавателя)
        $roleResponse = $this->actingAs($admin)->patch(route('admin.users.role', $reader), [
            'role' => 'journalist',
        ]);
        $roleResponse->assertRedirect(route('admin'));
        $this->assertEquals('journalist', $reader->fresh()->role);

        // 4. Админ удаляет новость
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.destroy', $news));
        $deleteResponse->assertRedirect(route('admin'));
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    public function test_quick_login_works_for_demo(): void
    {
        $admin = User::create([
            'name' => 'Админ',
            'email' => 'admin@test.ru',
            'password' => bcrypt('12345'),
            'role' => 'admin',
        ]);

        $response = $this->get(route('quick-login', 'admin'));
        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($admin);
    }
}
