<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // 1. Главная страница — лента всех новостей с возможностью фильтрации
    public function index(Request $request)
    {
        $selectedCategoryId = $request->query('category_id');

        $query = News::with(['category', 'user'])->latest();

        if (! empty($selectedCategoryId)) {
            $query->where('category_id', $selectedCategoryId);
        }

        $news = $query->get();
        $categories = Category::all();

        return view('pages.home', compact('news', 'categories', 'selectedCategoryId'));
    }

    // 2. Каталог — лента новостей по выбранной категории (выпадающий список / табы)
    public function catalog(Request $request)
    {
        $categories = Category::all();
        $selectedCategoryId = $request->query('category_id', $categories->first()?->id);
        $selectedCategory = $categories->where('id', $selectedCategoryId)->first() ?? $categories->first();

        $news = $selectedCategory
            ? $selectedCategory->news()->with('user')->latest()->get()
            : collect();

        return view('pages.catalog', compact('news', 'categories', 'selectedCategory'));
    }

    // 3. Журналист — страница добавления новой статьи
    public function journalist()
    {
        $categories = Category::all();

        return view('pages.journalist', compact('categories'));
    }

    // Обработка формы создания новости от журналиста
    public function store(Request $request)
    {
        // Валидация строго по ID существующей категории (защита от изменения на фронтенде)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
        ]);

        // Автоматическая привязка автора (текущего пользователя)
        $validated['user_id'] = auth()->id();

        News::create($validated);

        return redirect()->route('home')->with('success', 'Статья успешно опубликована!');
    }

    // Редактирование статьи администратором
    public function edit(News $news)
    {
        $categories = Category::all();

        return view('pages.news-edit', compact('news', 'categories'));
    }

    // Обновление статьи администратором
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
        ]);

        $news->update($validated);

        return redirect()->route('admin')->with('success', 'Статья #'.$news->id.' успешно обновлена!');
    }

    // 4. Администратор — страница управления новостями и ролями пользователей
    public function admin()
    {
        $news = News::with(['category', 'user'])->latest()->get();
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
