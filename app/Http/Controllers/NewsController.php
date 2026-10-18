<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // Список предопределенных тем для выпадающих списков
    private array $defaultCategories = [
        'Искусственный интеллект',
        'Технологии',
        'Космос',
        'Наука',
    ];

    // 1. Главная страница — лента всех новостей с возможностью фильтрации
    public function index(Request $request)
    {
        $selectedCategory = $request->query('category');

        $query = News::latest();

        if (! empty($selectedCategory)) {
            $query->where('category', $selectedCategory);
        }

        $news = $query->get();

        // Получаем все уникальные категории из базы данных для фильтра
        $categories = News::select('category')->distinct()->pluck('category')->toArray();
        if (empty($categories)) {
            $categories = $this->defaultCategories;
        }

        return view('pages.home', compact('news', 'categories', 'selectedCategory'));
    }

    // 2. Каталог — лента новостей по выбранной категории (выпадающий список / табы)
    public function catalog(Request $request)
    {
        $selectedCategory = $request->query('category', 'Искусственный интеллект');

        $news = News::where('category', $selectedCategory)->latest()->get();

        $categories = News::select('category')->distinct()->pluck('category')->toArray();
        if (empty($categories)) {
            $categories = $this->defaultCategories;
        }

        return view('pages.catalog', compact('news', 'categories', 'selectedCategory'));
    }

    // 3. Журналист — страница добавления новой статьи
    public function journalist()
    {
        $categories = $this->defaultCategories;

        return view('pages.journalist', compact('categories'));
    }

    // Обработка формы создания новости от журналиста
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
        ]);

        if (empty($validated['category'])) {
            $validated['category'] = 'Искусственный интеллект';
        }

        News::create($validated);

        return redirect()->route('home')->with('success', 'Статья успешно опубликована!');
    }

    // Редактирование статьи администратором
    public function edit(News $news)
    {
        $categories = $this->defaultCategories;

        return view('pages.news-edit', compact('news', 'categories'));
    }

    // Обновление статьи администратором
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
        ]);

        $news->update($validated);

        return redirect()->route('admin')->with('success', 'Статья #'.$news->id.' успешно обновлена!');
    }

    // 4. Администратор — страница управления новостями и ролями пользователей
    public function admin()
    {
        $news = News::latest()->get();
        $users = User::latest()->get();

        return view('pages.admin', compact('news', 'users'));
    }

    // Удаление новости администратором
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('admin')->with('success', 'Новость успешно удалена!');
    }

    // Изменение роли пользователя администратором
    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,journalist,user',
        ]);

        // Предотвращение случайного снятия роли у самого себя
        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->withErrors(['role' => 'Вы не можете снять роль администратора с самого себя.']);
        }

        $user->update(['role' => $validated['role']]);

        return redirect()->route('admin')->with('success', 'Роль пользователя '.$user->name.' изменена на «'.$user->role_title.'»!');
    }
}
