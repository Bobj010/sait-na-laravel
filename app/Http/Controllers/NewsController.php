<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // 1. Главная страница — лента всех новостей
    public function index()
    {
        $news = News::latest()->get();

        return view('pages.home', compact('news'));
    }

    // 2. Каталог — лента новостей только по теме «Искусственный интеллект»
    public function catalog()
    {
        $category = 'Искусственный интеллект';
        $news = News::where('category', $category)->latest()->get();

        return view('pages.catalog', compact('news', 'category'));
    }

    // 3. Журналист — страница добавления новой статьи
    public function journalist()
    {
        return view('pages.journalist');
    }

    // Обработка формы создания новости от журналиста
    public function store(Request $request)
    {
        // Простая валидация полей
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
        ]);

        // Если категория не выбрана, ставим по умолчанию
        if (empty($validated['category'])) {
            $validated['category'] = 'Искусственный интеллект';
        }

        News::create($validated);

        return redirect()->route('home')->with('success', 'Статья успешно опубликована!');
    }

    // 4. Администратор — страница управления новостями
    public function admin()
    {
        $news = News::latest()->get();

        return view('pages.admin', compact('news'));
    }

    // Удаление новости администратором (простой полезный функционал)
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('admin')->with('success', 'Новость успешно удалена!');
    }
}
