{{-- Страница 4: Администратор — панель управления и мониторинга --}}
<x-layout title="Администратор">
    <div class="page-header">
        <h1 class="page-title">Панель администратора</h1>
        <p class="page-subtitle">Обзор всех опубликованных новостей и базовое управление контентом</p>
    </div>

    {{-- Простые блоки со статистикой сайта --}}
    <div class="admin-stats">
        <div class="stat-box">
            <div class="stat-number">{{ $news->count() }}</div>
            <div class="stat-label">Всего публикаций</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $news->where('category', 'Искусственный интеллект')->count() }}</div>
            <div class="stat-label">По теме «ИИ»</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $news->where('category', '!=', 'Искусственный интеллект')->count() }}</div>
            <div class="stat-label">Другие темы</div>
        </div>
    </div>

    {{-- Таблица новостей --}}
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Заголовок</th>
                    <th style="width: 180px;">Категория</th>
                    <th style="width: 150px;">Дата добавления</th>
                    <th style="width: 100px; text-align: center;">Действие</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($news as $item)
                    <tr>
                        <td><strong>#{{ $item->id }}</strong></td>
                        <td>{{ $item->title }}</td>
                        <td>
                            <span class="news-category">{{ $item->category }}</span>
                        </td>
                        <td>{{ $item->created_at ? $item->created_at->format('d.m.Y H:i') : '—' }}</td>
                        <td style="text-align: center;">
                            <form action="{{ route('admin.destroy', $item) }}" method="POST" onsubmit="return confirm('Вы действительно хотите удалить эту новость?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #64748b; padding: 30px;">
                            Новостей в базе данных пока нет.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
