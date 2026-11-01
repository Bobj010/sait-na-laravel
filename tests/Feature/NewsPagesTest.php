<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsPagesTest extends TestCase
{
    use RefreshDatabase;

    private Category $aiCategory;

    private Category $spaceCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->aiCategory = Category::create(['name' => 'Искусственный интеллект']);
        $this->spaceCategory = Category::create(['name' => 'Космос']);
    }

    public function test_home_page_is_accessible_and_filters_categories(): void
    {
        News::create([
            'title' => 'Тестовая новость ИИ',
            'category_id' => $this->aiCategory->id,
            'content' => 'Текст про ИИ',
        ]);

        News::create([
            'title' => 'Тестовая новость Космос',
            'category_id' => $this->spaceCategory->id,
            'content' => 'Текст про Космос',
        ]);

        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('Лента всех новостей');
        $response->assertSee('Тестовая новость ИИ');
        $response->assertSee('Тестовая новость Космос');

        // Фильтрация по категории через category_id
        $filteredResponse = $this->get(route('home', ['category_id' => $this->aiCategory->id]));
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('Тестовая новость ИИ');
        $filteredResponse->assertDontSee('Тестовая новость Космос');
    }

    public function test_catalog_page_is_accessible_and_filters(): void
    {
        News::create([
            'title' => 'Новость про ИИ',
            'category_id' => $this->aiCategory->id,
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

    public function test_journalist_can_create_news_with_automatic_author_binding(): void
    {
        $journalist = User::create([
            'name' => 'Иван Журналист',
            'email' => 'journ@test.ru',
            'password' => bcrypt('12345'),
            'role' => 'journalist',
        ]);

        // Журналист может зайти в раздел /journalist
        $this->actingAs($journalist)->get(route('journalist'))->assertStatus(200);

        // Журналист может опубликовать новость
        $postResponse = $this->actingAs($journalist)->post(route('journalist.store'), [
            'title' => 'Статья от журналиста',
            'category_id' => $this->aiCategory->id,
            'content' => 'Текст от журналиста',
        ]);

        $postResponse->assertRedirect(route('home'));
        $this->assertDatabaseHas('news', [
            'title' => 'Статья от журналиста',
            'category_id' => $this->aiCategory->id,
            'user_id' => $journalist->id,
        ]);

        // Проверяем отображение автора на главной
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertSee('Иван Журналист');

        // Но журналист не имеет доступа в админку
        $this->actingAs($journalist)->get(route('admin'))->assertStatus(403);
    }

    public function test_cannot_inject_fake_category_on_frontend_mironov_vulnerability_fix(): void
    {
        $journalist = User::create([
            'name' => 'Журналист Тестер',
            'email' => 'tester@test.ru',
            'password' => bcrypt('12345'),
            'role' => 'journalist',
        ]);

        // Попытка подделать категорию на фронтенде (передать несуществующий category_id: 99999)
        $response = $this->actingAs($journalist)->post(route('journalist.store'), [
            'title' => 'Попытка взлома категорий',
            'category_id' => 99999,
            'content' => 'Этот текст не должен сохраниться в базе данных',
        ]);

        // Контроллер отклоняет запрос с ошибкой валидации
        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseMissing('news', [
            'title' => 'Попытка взлома категорий',
        ]);
    }

    public function test_one_to_many_relationships_work_correctly(): void
    {
        $author = User::create([
            'name' => 'Автор Связей',
            'email' => 'rel_author@test.ru',
            'password' => bcrypt('12345'),
            'role' => 'journalist',
        ]);

        $news = News::create([
            'title' => 'Проверка связей 1:N',
            'category_id' => $this->spaceCategory->id,
            'user_id' => $author->id,
            'content' => 'Проверка belongsTo и hasMany',
        ]);

        // Проверка связи News -> Category (belongsTo)
        $this->assertEquals('Космос', $news->category->name);

        // Проверка связи News -> User (belongsTo)
        $this->assertEquals('Автор Связей', $news->user->name);

        // Проверка связи Category -> News (hasMany)
        $this->assertTrue($this->spaceCategory->news->contains($news));

        // Проверка связи User -> News (hasMany)
        $this->assertTrue($author->news->contains($news));
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
            'category_id' => $this->aiCategory->id,
            'user_id' => $admin->id,
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
            'category_id' => $this->spaceCategory->id,
            'content' => 'Обновленный текст статьи',
        ]);
        $updateResponse->assertRedirect(route('admin'));
        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'title' => 'Отредактированный заголовок',
            'category_id' => $this->spaceCategory->id,
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

    public function test_quick_login_auto_heals_when_user_is_missing(): void
    {
        // Удаляем всех пользователей для проверки чистого состояния
        User::query()->delete();
        $this->assertDatabaseCount('users', 0);

        // Быстрый вход администратора должен автоматически восстановить пользователя
        $response = $this->get(route('quick-login', 'admin'));
        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertEquals('admin', auth()->user()->role);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@test.ru',
            'role' => 'admin',
        ]);
    }
}
