{{-- Страница 1: Главная — лента всех новостей с фильтрацией по категориям --}}
<x-layout title="Главная">
    <div class="page-header">
        <div>
            <h1 class="page-title">Лента всех новостей</h1>
            <p class="page-subtitle">Последние события, обновления и актуальные публикации</p>
        </div>

        {{-- Выпадающий список категорий из базы данных --}}
        <div>
            <form method="GET" action="{{ route('home') }}" style="display: flex; gap: 8px; align-items: center;">
                <label for="category-select" style="font-size: 0.85rem; font-weight: 600; color: #475569;">Тема:</label>
                <select id="category-select" name="category_id" class="form-select-sm" onchange="this.form.submit()">
                    <option value="">Все категории ({{ $news->count() }})</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string)$selectedCategoryId === (string)$cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @if ($selectedCategoryId)
                    <a href="{{ route('home') }}" class="btn-sm btn-secondary" style="text-decoration: none;" title="Сбросить фильтр">✕</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Быстрые кнопки/табы категорий --}}
    <div class="filter-bar">
        <span class="filter-label">🏷️ Категории:</span>
        <div class="category-pills">
            <a href="{{ route('home') }}" class="category-pill {{ empty($selectedCategoryId) ? 'active' : '' }}">
                Все
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('home', ['category_id' => $cat->id]) }}" class="category-pill {{ (string)$selectedCategoryId === (string)$cat->id ? 'active' : '' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    @if ($news->count() > 0)
        {{-- Вертикальный список карточек по рисунку на доске --}}
        <div class="news-list">
            @foreach ($news as $item)
                <x-news-card :news="$item" />
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <h3>В выбранной категории пока нет новостей</h3>
            <p><a href="{{ route('home') }}" style="color: #3b82f6; text-decoration: none;">Показать все новости</a> или перейдите в раздел <a href="{{ route('journalist') }}">«Журналист»</a> для публикации.</p>
        </div>
    @endif
</x-layout>
